<?php

use \app\components\Form;
use \app\components\ActiveForm;
use app\components\Html;

/**
 * @var \yii\web\View $this
 * @var \prime\models\ar\Project $model
 */

$this->title = Yii::t('app', 'Create project');

$this->params['subMenu'] = [
    'items' => [
        '<li>' . Html::submitButton(\Yii::t('app', 'Create project'), ['form' => 'create-project', 'class' => 'btn btn-primary']) . '</li>'
    ]
];
?>

<div class="col-xs-12">
    <?php
    $form = ActiveForm::begin([
        'id' => 'create-project',
        'method' => 'POST',
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
            'title' => [
                'type' => Form::INPUT_TEXT,
            ],
            'description' => [
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => \dosamigos\ckeditor\CKEditor::class,
                'options' => [
                    'preset' => 'basic'
                ]
            ],
            'owner_id' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => $model->ownerOptions()
            ],
            'tool_id' => [
                'type' => Form::INPUT_DROPDOWN_LIST,
                'items' => $model->toolOptions()
            ],
            'data_survey_eid' => [
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => \kartik\widgets\DepDrop::class,
                'options' => [
                    'pluginOptions' => [
                        'url' => ['/projects/dependent-surveys'],
                        'depends' => ['createupdate-tool_id'],
//                        'initialize' => true,
                    ],
                ],
                'enableClientValidation' => false
            ],
            'token' => [
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => \kartik\widgets\DepDrop::class,
                'options' => [
                    'pluginOptions' => [
                        'url' => ['/projects/dependent-tokens'],
                        'depends' => ['createupdate-data_survey_eid'],
                        'initialize' => false,
                        'placeholder' => \Yii::t('app', 'Create new token')
                    ],
                ],
                'enableClientValidation' => false
            ],

            'default_generator' => [
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => \kartik\widgets\DepDrop::class,
                'options' => [
                    'pluginOptions' => [
                        'url' => ['/tools/dependent-generators'],
                        'depends' => ['createupdate-tool_id'],
                        'initialize' => true,
                        'placeholder' => null
                    ],
                ],
                'enableClientValidation' => false
            ],
            'country_iso_3' => [
                'type' => Form::INPUT_WIDGET,
                'widgetClass' => \kartik\widgets\Select2::class,
                'options' => [
                    'data' => $model->countryOptions(),
                    'pluginOptions' => [
                        'placeholder' => \Yii::t('app', 'Select country')
                    ]
                ],
                'hint' => \Yii::t('app', "All projects will be located in their respective countries. Set latitude and longitude only if you want this project to be specifically located at the subnational level (in a particular region or area).
To set latitude and longitude either enter them manually (decimal degrees) or drag and drop the placemarker on the map")
            ],
            'locality_name' => [
                'type' => Form::INPUT_TEXT
            ],
            'latitude' => [
                'type' => Form::INPUT_TEXT
            ],
            'longitude' => [
                'type' => Form::INPUT_TEXT
            ],
            'map' => [
                'type' => Form::INPUT_RAW,
                'value' => function(\prime\models\forms\projects\CreateUpdate $model, $index, Form $form) {
                    $this->registerAssetBundle(\prime\assets\LocationPickerAsset::class);
                    $this->registerJs("$('#{$form->getId()} .location-picker').locationpicker({
                        zoom: 2,
                        radius: false,
                        inputBinding: {
                            latitudeInput: $('#{$form->getId()} [name=\'" . Html::getInputName($model, 'latitude') . "\']'),
                            longitudeInput: $('#{$form->getId()} [name=\'" . Html::getInputName($model, 'longitude') . "\']')
                        },
                        oninitialized: function(component) {
                            var e = $.Event('change');
                            e.originalEvent = true;
                            this.inputBinding.latitudeInput.val(this.inputBinding.latitudeInput.attr('value')).trigger(e);
                            this.inputBinding.longitudeInput.val(this.inputBinding.longitudeInput.attr('value')).trigger(e);
                        }
                    });
                    ");
                    return Html::beginTag('div', ['class' => 'form-group']) .
                    Html::beginTag('div', ['class' => 'col-md-offset-2 col-md-10']) .
                    Html::tag('div', '', ['style' => ['height' => '400px'], 'class' => ['form-control', 'location-picker']]) .
                    Html::endTag('div') .
                    Html::tag('div', '', ['class' => 'col-md-offset-2 col-md-10']) .
                    Html::beginTag('div', ['class' => 'col-md-offset-2 col-md-10']) .
                    Html::tag('div', '', ['class' => 'help-block']) .
                    Html::endTag('div') .
                    Html::endTag('div');
                }
            ]
        ]
    ]);

    $form->end();
    ?>
</div>