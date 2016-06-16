<?php

use app\components\Html;
use \prime\models\permissions\Permission;
use prime\models\ar\Setting;

/**
 * @var \prime\models\ar\Project $model
 * @var \yii\web\View $this
 * @var \yii\data\ArrayDataProvider $responsesDataProvider
 */

$this->params['subMenu']['items'] = [];

$this->params['subMenu']['items'][] = [
    'label' => Html::icon(Setting::get('icons.limeSurveyUpdate')),
    'url' => ['/projects/update-lime-survey', 'id' => $model->id],//$model->surveyUrl,
    'visible' => $model->userCan(Permission::PERMISSION_WRITE, app()->user->identity) && $model->closed === null,
    'options' => [
        'class' => 'icon',
        'title' => \Yii::t('app', 'Data update'),
    ],
];

if(isset($model->defaultGenerator)) {
    $this->params['subMenu']['items'][] = [
        'label' => Html::icon(Setting::get('icons.preview')),
        'options' => [
            'class' => 'icon',
            'title' => \Yii::t('app', 'Preview report'),
        ],
        'url' => [
            '/reports/preview',
            'projectId' => $model->id,
            'reportGenerator' => $model->default_generator
        ],
        'visible' => $model->userCan(Permission::PERMISSION_WRITE, app()->user->identity) && ($model->getResponses()->size() > 0) && $model->closed === null
    ];
}

$this->params['subMenu']['items'][] = [
    'label' => Html::icon(Setting::get('icons.share')),
    'url' => ['/projects/share', 'id' => $model->id],
    'visible' => $model->userCan(Permission::PERMISSION_SHARE, app()->user->identity) && $model->closed === null,
    'options' => [
        'class' => 'icon',
        'title' => \Yii::t('app', 'Share'),
    ],
];

$this->params['subMenu']['items'][] = [
    'label' => Html::icon(Setting::get('icons.download', 'download-alt')),
    'url' => ['/projects/download', 'id' => $model->id],
    'visible' => $model->userCan(Permission::PERMISSION_ADMIN, app()->user->identity) && $model->getResponses()->size() > 0,
    'options' => [
        'download' => true,
        'class' => 'icon',
        'id' => 'download-data',
        'title' => \Yii::t('app', 'Download'),
    ],
];
$this->registerJs(<<<SCRIPT
var handler = function(e){
    e.preventDefault();
    e.stopPropagation();
    bootbox.dialog({
        message: "Do you prefer answer as text or as code?",
        title: "Download data in CSV format",
        onEscape: function() {
            console.log('cb'); 
        },
        buttons: {
            text: {
                label: "Text",
                callback: function() {
                    console.log('text plz');
                }
            },
            code: {
                label: "Code",
                callback: function() {
                    console.log('code plz');
                }
            },
        }
    
    });
};
$('#download-data').on('click', handler);
SCRIPT
);


$this->params['subMenu']['items'][] = [
    'label' => Html::icon(Setting::get('icons.close')),
    'url' => ['/projects/close', 'id' => $model->id],
    'options' => [
        'class' => 'icon',
        'title' => \Yii::t('app', 'Deactivate'),
    ],
    'linkOptions' => [
        'data-confirm' => \Yii::t('app', 'Are you sure you want to close project <strong>{modelName}</strong>?', ['modelName' => $model->title]),
        'data-method' => 'delete'
    ],
    'visible' => $model->userCan(Permission::PERMISSION_ADMIN, app()->user->identity) && $model->closed === null
];

$this->params['subMenu']['items'][] = [
    'label' => Html::icon(Setting::get('icons.open')),
    'url' => ['/projects/re-open', 'id' => $model->id],
    'options' => [
        'class' => 'icon',
        'title' => \Yii::t('app', 'Reactivate'),
    ],
    'linkOptions' => [
        'data-confirm' => \Yii::t('app', 'Are you sure you want to re-open project <strong>{modelName}</strong>?', ['modelName' => $model->title]),
        'data-method' => 'put'
    ],
    'visible' => $model->userCan(Permission::PERMISSION_ADMIN, app()->user->identity) && $model->closed !== null
];

?>

<div class="col-xs-12">
    <div class="row">
        <div class="col-xs-10">
            <h1><?=$model->title?><?=$model->userCan(Permission::PERMISSION_ADMIN, app()->user->identity) && $model->closed === null ? Html::a(Html::icon(\prime\models\ar\Setting::get('icons.update'), ['title' => \Yii::t('app', 'Project settings')]), ['projects/update', 'id' => $model->id]) : ''?></h1>
        </div>
        <div class="col-xs-2">
            <?=Html::img($model->tool->imageUrl, ['style' => ['width' => '90%']])?>
        </div>

    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-7 col-md-9">
        </div>
        <div class="col-xs-12 col-sm-5 col-md-3">
            <?=isset($model->owner) ?
            \prime\widgets\User::widget([
                'user' => $model->owner
            ])
                :''
            ?>
        </div>
    </div>
</div>
<?php
    if (isset($model->tool->progress_type)) {
        echo Html::tag('iframe', '', [
            'src' => \yii\helpers\Url::to(['/projects/progress', 'id' => $model->id]),
            'class' => ['col-xs-12', 'resize'],
            'style' => [
                'height' => 0,
                'border' => 0,
                'padding-left' => 0,
                'padding-right' => 0,
                'padding-bottom' => 0
            ]
        ]);
    }
?>


<div class="col-xs-12">
    <?php

    // Dynamically resize iframe.
    $this->registerAssetBundle(\prime\assets\ReportResizeAsset::class);
    echo \yii\bootstrap\Tabs::widget([
         'items' => [
             [
                 'label' => \Yii::t('app', 'Reports'),
                 'content' => $this->render('read/reports.php', ['tool' => $model->tool, 'model' => $model])
             ],
             [
                 'label' => \Yii::t('app', 'Responses'),
                 'content' => $this->render('read/responses.php', [
                     'tool' => $model->tool,
                     'model' => $model,
                     'responses' => $model->getResponses()
                 ])
             ]
         ]
    ]);
    ?>
</div>

