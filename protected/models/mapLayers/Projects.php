<?php

namespace prime\models\mapLayers;

use prime\models\ar\Project;
use prime\models\ar\ProjectCountry;
use prime\models\ar\Report;
use prime\models\Country;
use prime\models\MapLayer;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;

class Projects extends MapLayer
{
    public function init()
    {
        parent::init();
        $this->allowPointSelect = true;
        $this->joinBy = null;
        $this->name = \Yii::t('app', 'Projects');
        $this->showInLegend = true;
        $this->addPointEventHandler('select', new JsExpression("function(e){select(this, 'projects'); return false;}"));
        $this->type = 'mappoint';
    }

    protected function prepareData()
    {
        $this->data = array_map(function($project) {
            /** @var Project $project */
            $latLong = $project->getLatLong();
            return [
                'name' => $project->title,
                'lat' => $latLong->getLatitude()->get(),
                'lon' => $latLong->getLongitude()->get(),
                'id' => $project->id
            ];
        }, Project::find()->notClosed()->all());
    }


}