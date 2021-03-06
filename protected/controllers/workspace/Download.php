<?php


namespace prime\controllers\workspace;


use prime\interfaces\HeramsResponseInterface;
use prime\models\ar\Workspace;
use prime\models\permissions\Permission;
use SamIT\LimeSurvey\Interfaces\AnswerInterface;
use SamIT\LimeSurvey\Interfaces\QuestionInterface;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use SamIT\LimeSurvey\JsonRpc\Concrete\Survey;
use yii\base\Action;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\User;
use function iter\toArray;

class Download extends Action
{
    public function run(
        Response $response,
        User $user,
        int $id,
        $text = false
    ) {
        $codeAsText = $text;
        $workspace = Workspace::findOne(['id' => $id]);
        if (!isset($workspace)) {
            throw new NotFoundHttpException();
        }
        if (!(
            $user->can(Permission::PERMISSION_ADMIN, $workspace)
            || $user->can(Permission::PERMISSION_WRITE, $workspace->project)
        )) {
            throw new ForbiddenHttpException();
        }

        /** @var Survey $survey */
        $survey = $workspace->project->getSurvey();
        /** @var QuestionInterface[] $questions */
        $questions = [];
        foreach($survey->getGroups() as $group) {
            foreach($group->getQuestions() as $question) {
                // Extract each question separately.
                $questions[$question->getTitle()] = $question;
            }
        }
        $rows = [];
        $codes = [];
        /** @var HeramsResponseInterface $record */
        foreach($workspace->getResponses()->each() as $record) {
            $row = [];
            $rows[] = toArray($this->getRow($survey, $record));
        }

//        echo '<pre>';
//        print_r($rows);
//        die();
        $stream = fopen('php://temp', 'w+');
        // First get all columns.
        $columns = [];
        foreach($rows as $row) {
            foreach($row as $key => $dummy) {
                $columns[$key] = true;
            }
        }

        if (!empty($columns)) {
            fputcsv($stream, array_keys($columns));
            $header = [];
            foreach(array_keys($columns) as $columnName) {
                $header[] = $codes[$columnName];
            }
            fputcsv($stream, $header);

            /** @var ResponseInterface $record */
            foreach ($rows as $data) {
                $row = [];
                foreach(array_keys($columns) as $column) {
                    $row[$column] = isset($data[$column]) ? $data[$column] : null;
                }
                fputcsv($stream, $row);
            }
        }
        return $response->sendStreamAsFile($stream, "{$workspace->title}.csv", [
            'mimeType' => 'text/csv'
        ]);
    }

    private function getRow(
        SurveyInterface $survey,
        HeramsResponseInterface $record
    ) {
        $data = $record->getRawData();

        foreach($survey->getGroups() as $group) {
            foreach($group->getQuestions() as $question) {
                // Extract each question separately.
                switch ($question->getDimensions()) {
                    case 0:
                        if ($question->getAnswers() === null) {
                            yield $question->getTitle() => $data[$question->getTitle()] ?? null;
                        } else {
                            $map = ArrayHelper::map($question->getAnswers(),
                                function(AnswerInterface $a) { return $a->getCode(); },
                                function(AnswerInterface $a) { return $a->getText(); }
                            );
                            yield $question->getTitle() => $map[$data[$question->getTitle()] ?? null] ?? null;
                        }

                        break;
                    case 1:
                        foreach($question->getQuestions(0) as $subQuestion) {
                            if ($subQuestion->getAnswers() === null) {
                                yield "{$question->getTitle()}[{$subQuestion->getTitle()}]" => $data["{$question->getTitle()}[{$subQuestion->getTitle()}]"] ?? null;
                            } else {
                                $map = ArrayHelper::map($subQuestion->getAnswers(),
                                    function(AnswerInterface $a) { return $a->getCode(); },
                                    function(AnswerInterface $a) { return $a->getText(); }
                                );
                                if (isset($data[$question->getTitle()])) {
                                    var_dump($question->getTitle());

                                    var_dump($data[$question->getTitle()]);
                                    var_dump($subQuestion->getTitle());
                                    var_dump($subQuestion->getText());
                                    die();
                                }
                                yield "{$question->getTitle()}[{$subQuestion->getTitle()}]" => $map[$data[$question->getTitle()] ?? null] ?? null;
                            }
                        }
                        break;
                    case 2:
                        $rowQuestions = $question->getQuestions(0);
                        usort($rowQuestions, function(QuestionInterface $a, QuestionInterface $b) {
                            return $a->getIndex() <=> $b->getIndex();
                        });
                        foreach($rowQuestions as $rowQuestion) {
                            $cells = $rowQuestion->getQuestions(0);
                            usort($cells, function(QuestionInterface $a, QuestionInterface $b) {
                                return $a->getIndex() <=> $b->getIndex();
                            });
                            foreach($cells as $cell) {
                                $code = "{$question->getTitle()}[{$rowQuestion->getTitle()}_{$cell->getTitle()}]";
                                yield $code => $data[$code] ?? null;
                            }
                        }
                        break;
                    default:
                }
            }
        }
    }

    private function getAnswer(QuestionInterface $q, $value, $text = false)
    {
        if (empty($value)) {
            return "(not set)";
        } elseif ($text && (null !== $answers = $q->getAnswers())) {
            foreach($answers as $answer) {
                if ($answer->getCode() == $value) {
                    return $answer->getText();
                }
            }
            return "Invalid answer : `$value`.";
        } else {
            return $value;
        }

    }

}