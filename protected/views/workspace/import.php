<?php

use app\components\Form;
use kartik\widgets\ActiveForm;
use yii\bootstrap\Html;

/**
 * @var \yii\web\View $this
 * @var \prime\models\forms\workspace\Import $model
 * @var \prime\models\ar\Project $project
 */

$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Admin dashboard'),
    'url' => ['/admin']
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Projects'),
    'url' => ['/project']
];
$this->params['breadcrumbs'][] = [
    'label' => $project->title,
    'url' => ['project/update', 'id' => $project->id]
];
$this->params['breadcrumbs'][] = [
    'label' => 'Workspaces',
    'url' => ['project/workspaces', 'id' => $project->id]
];
$this->title = \Yii::t('app', 'Import');
$this->params['breadcrumbs'][] = $this->title;


?>

<div class="col-xs-12">
    <?php
    $form = ActiveForm::begin([
        "type" => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => [
            'showLabels' => true,
            'defaultPlaceholder' => false
        ]
    ]);

    echo \app\components\Form::widget([
        'form' => $form,
        'model' => $model,
        'columns' => 1,
        "attributes" => [
            'titleField' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => $model->fieldOptions()
            ],
            'tokens' => [
                'type' => Form::INPUT_CHECKBOX_LIST,
                'items' => $model->tokenOptions()
            ]
        ]
    ]);
    echo \yii\bootstrap\ButtonGroup::widget([
        'options' => [
            'class' => [
                'pull-right'
            ],
        ],
        'buttons' => [
            Html::submitButton(\Yii::t('app', 'Import workspaces'), ['class' => 'btn btn-primary']),
        ]
    ]);
    $form->end();
    ?>
</div>
