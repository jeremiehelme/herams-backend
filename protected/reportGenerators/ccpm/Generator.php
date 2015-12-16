<?php

namespace prime\reportGenerators\ccpm;

use prime\factories\GeneratorFactory;
use prime\interfaces\ProjectInterface;
use prime\interfaces\ReportGeneratorInterface;
use prime\interfaces\ReportInterface;
use prime\interfaces\ResponseCollectionInterface;
use prime\interfaces\SignatureInterface;
use prime\interfaces\SurveyCollectionInterface;
use prime\interfaces\UserDataInterface;
use prime\models\ar\Response;
use prime\objects\Report;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;
use yii\helpers\ArrayHelper;

class Generator extends \prime\reportGenerators\base\Generator
{
    public $CPASurveyId = 67825;
    public $PPASurveyId = 22814;

    public function calculateScore(ResponseCollectionInterface $responses, $map, $method = 'median')
    {
        $result = $this->getQuestionValues($responses, $map, [$this, 'rangeValidator04']);
        if (!empty($result)) {
            switch ($method) {
                case 'average':
                    $result = average($result);
                    break;
                case 'median':
                    $result = median($result);
                    break;
            }

            return $this->map04($result);
        }
    }

    public function calculateDistribution(ResponseCollectionInterface $responses, $map)
    {
        $tempResult = [];

        foreach($map as $surveyId => $questionIds) {
            if(!isset($tempResult[$surveyId])) {
                $tempResult[$surveyId] = [];
            }
            $values = $this->getQuestionValues($responses, [$surveyId => $questionIds], [$this, 'rangeValidator04']);
            foreach($values as $value)
            {
                if(!isset($tempResult[$surveyId][$value])) {
                    $tempResult[$surveyId][$value] = 0;
                }
                $tempResult[$surveyId][$value]++;
            }
        }

        $result = [];
        foreach($tempResult as $surveyId => $values) {
            $result[$surveyId] = [];
            for($i = 0; $i <= 4; $i++) {
                $result[$surveyId][$i] = array_sum($values) > 0 ? ArrayHelper::getValue($values, $i, 0) / array_sum($values) : 0;
            }
        }

        return $result;
    }

    public function getResponseRates(ResponseCollectionInterface $responses)
    {
        $result = [];
        $responsesPerType = array_count_values($this->getQuestionValues($responses, [$this->PPASurveyId => ['q012']]));
        $responsesPerType['total'] = array_sum($responsesPerType);
        $totalsPerType = [
            1 => (int) ArrayHelper::getValue($this->getQuestionValues($responses, [$this->CPASurveyId => ['q012[1]']]), 0, 0),
            2 => (int) ArrayHelper::getValue($this->getQuestionValues($responses, [$this->CPASurveyId => ['q012[2]']]), 0, 0),
            3 => (int) ArrayHelper::getValue($this->getQuestionValues($responses, [$this->CPASurveyId => ['q012[3]']]), 0, 0),
            4 => (int) ArrayHelper::getValue($this->getQuestionValues($responses, [$this->CPASurveyId => ['q012[4]']]), 0, 0),
            5 => (int) ArrayHelper::getValue($this->getQuestionValues($responses, [$this->CPASurveyId => ['q012[5]']]), 0, 0),
            6 => (int) ArrayHelper::getValue($this->getQuestionValues($responses, [$this->CPASurveyId => ['q012[6]']]), 0, 0),
        ];
        $totalsPerType['total'] = array_sum($totalsPerType);

        $totalsPerType2 = [
            1 => (int) ArrayHelper::getValue($this->getQuestionValues($responses, [$this->CPASurveyId => ['q013[1]']]), 0, 0),
            2 => (int) ArrayHelper::getValue($this->getQuestionValues($responses, [$this->CPASurveyId => ['q013[2]']]), 0, 0),
            3 => (int) ArrayHelper::getValue($this->getQuestionValues($responses, [$this->CPASurveyId => ['q013[3]']]), 0, 0),
            4 => (int) ArrayHelper::getValue($this->getQuestionValues($responses, [$this->CPASurveyId => ['q013[4]']]), 0, 0),
            5 => (int) ArrayHelper::getValue($this->getQuestionValues($responses, [$this->CPASurveyId => ['q013[5]']]), 0, 0),
            6 => (int) ArrayHelper::getValue($this->getQuestionValues($responses, [$this->CPASurveyId => ['q013[6]']]), 0, 0),
        ];
        $totalsPerType2['total'] = array_sum($totalsPerType2);

        foreach ($totalsPerType as $number => $value) {
            $result[$number]['responses'] = ArrayHelper::getValue($responsesPerType, $number, 0);
            $result[$number]['total1'] = $totalsPerType[$number];
            $result[$number]['total2'] = $totalsPerType2[$number];
        }

        return $result;
    }

    /**
     * @return string the view path that may be prefixed to a relative view name.
     */
    public function getViewPath()
    {
        return __DIR__ . '/views/';
    }

    public function map04($value)
    {
        return $value * 25;
    }

    public function mapStatus($value)
    {
        $map = [
            25 => 'weak',
            50 => 'unsatisfactory',
            75 => 'satisfactory',
            100 => 'good'
        ];
        foreach($map as $max => $status) {
            if ($value <= $max) {
                return $status;
            }
        }
        return $status;
    }

    protected function rangeValidator04($value)
    {
        return $value != '' && $value >= 0 && $value <= 4;
    }

    /**
     * @param ResponseCollectionInterface $responses
     * @param SurveyCollectionInterface $surveys,
     * @param SignatureInterface $signature
     * @param ProjectInterface $project
     * @param UserDataInterface|null $userData
     * @return string
     */
    public function renderPreview(
        ResponseCollectionInterface $responses,
        SurveyCollectionInterface $surveys,
        ProjectInterface $project,
        SignatureInterface $signature = null,
        UserDataInterface $userData = null
    ) {
        return $this->view->render('preview', [
            'userData' => $userData,
            'project' => $project,
            'signature' => $signature,
            'responses' => $responses
        ], $this);
    }

    /**
     * This function renders a report.
     * All responses to be used are given as 1 array of Response objects.
     * @param ResponseCollectionInterface $responses
     * @param SurveyCollectionInterface $surveys
     * @param SignatureInterface $signature
     * @param ProjectInterface $project
     * @param UserDataInterface|null $userData
     * @return ReportInterface
     */
    public function render(
        ResponseCollectionInterface $responses,
        SurveyCollectionInterface $surveys,
        ProjectInterface $project,
        SignatureInterface $signature = null,
        UserDataInterface $userData = null
    ) {
        $stream = \GuzzleHttp\Psr7\stream_for($this->view->render('publish', [
            'userData' => $userData,
            'signature' => $signature,
            'responses' => $responses,
            'project' => $project
        ], $this));

        return new Report($userData, $signature, $stream, __CLASS__, $this->getReportTitle($project, $signature));
    }

    /**
     * Returns the title of the Report
     * @return string
     */
    public static function title()
    {
        return \Yii::t('ccpm', 'CCPM');
    }
}