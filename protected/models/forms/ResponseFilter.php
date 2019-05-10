<?php


namespace prime\models\forms;


use Carbon\Carbon;
use prime\objects\HeramsCodeMap;
use prime\objects\HeramsResponse;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use SamIT\LimeSurvey\JsonRpc\Concrete\Response;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\validators\DateValidator;
use yii\validators\SafeValidator;
use function iter\all;
use function iter\apply;
use function iter\enumerate;
use function iter\filter;

/**
 * Class ResponseFilter implements filtering for Response Collections
 * @package prime\models\forms
 */
class ResponseFilter extends Model
{
    public $date;

    public $types;
    public $locations = [];
    public $advanced = [];

    /**
     * @var HeramsResponse[]
     */
    private $allResponses;

    /**
     * @var SurveyInterface
     */
    private $survey;
    /**
     * @var HeramsCodeMap
     */
    private $map;

    /**
     * @param HeramsResponse[] $responses
     */
    public function __construct(
        array $responses,
        SurveyInterface $survey,
        HeramsCodeMap $map
    ) {
        parent::__construct([]);
        $this->allResponses = $responses;
        $this->date = date('Y-m-d');
        $this->survey = $survey;
        $this->map = $map;
    }

    public function rules()
    {
        return [
            [['date'], DateValidator::class, 'format' => 'php:Y-m-d'],
            [['locations', 'types', 'advanced'], SafeValidator::class],
        ];
    }

    private function makeNested(array $flat): array
    {

    }
    /**
     * @param Response[]
     * @return array
     */
    public function nestedLocationOptions(): array
    {
        $result = [];
        // Get the question.
        foreach($this->survey->getGroups() as $group) {
            foreach($group->getQuestions() as $question) {
                if ($question->getTitle() === $this->map->getLocation()) {
                    $answers = $question->getAnswers();
                    break 2;
                }
            }
        }

        if (isset($answers)) {
            foreach ($answers as $answer) {
                $result[$answer->getText()] = [$answer->getCode()];
                // Aggregates
                $keyParts = [];
                $parts = explode(' / ', $answer->getText());
                array_pop($parts);
                foreach($parts as $part) {
                    $keyParts[] = $part;
                    $key = implode(' / ', $keyParts);
                    if (!isset($result[$key])) {
                        $result[$key] = [$answer->getCode()];
                    } else {
                        $result[$key][] = $answer->getCode();
                    }


                }
            }

            return array_flip(array_map(function(array $list) { return implode(',', $list); }, $result));
        }

        $locations = [];
        /** @var HeramsResponse $response */
        foreach($this->allResponses as $response) {
            $location = array_filter([
                $response->getValueForCode('GEO1'),
                $response->getValueForCode('GEO2')
            ]);
            if (count($location) === 2) {
                $locations[$location[0]][$location[1]] = $location[1];
            }

        }
        return $locations;
    }

    public function filter(): array
    {
        \Yii::beginProfile('filter');
        $limit = new Carbon($this->date);
        // Index by UOID.
        /** @var HeramsResponse[] $indexed */
        $indexed = [];

        $locations = [];
        foreach((array) $this->locations as $location) {
            foreach(explode(',', $location) as $option) {
                $locations[$option] = true;
            }
        }
        apply(function(HeramsResponse $response) use (&$indexed) {
            $id = $response->getSubjectId();
            if (!isset($indexed[$id]) || $indexed[$id]->getDate() < $response->getDate()) {
                $indexed[$id] = $response;
            }
        }, filter(function(HeramsResponse $response) use ($limit, $locations) {
            // Date filter
            if ($response->getDate() === null || !$limit->greaterThan($response->getDate())) {
                return false;
            }

            // Type filter.
            if (!empty($this->types) && !in_array($response->getType(), $this->types)
            ) {
                return false;
            }

            // Location filter
            if (!isset($locations[$response->getLocation()])) {
                return false;
            }

            // Advanced filter.
            if (!all(function(array $pair) use ($response) {
                list($key, $allowedValues) = $pair;
                // Ignore empty filters.
                if (empty($allowedValues)) return true;
                $chosenValue = $response->getValueForCode($key);
                $chosenValues = is_array($chosenValue) ? $chosenValue : [$chosenValue];
                // We need overlap.
                return !empty(array_intersect($allowedValues, $chosenValues));
            }, enumerate($this->advanced))) {
                return false;
            }

            return true;

        }, $this->allResponses));

        \Yii::endProfile('filter');
        return array_values($indexed);
    }

    public function formName()
    {
        return 'RF';
    }


}