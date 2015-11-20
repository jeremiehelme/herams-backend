<?php

use miloschuman\highcharts\Highmaps;
use yii\web\JsExpression;

/**
 * @var \yii\web\View $this
 */

$this->params['subMenu']['items'] = [
    [
        'label' => \Yii::t('app', 'List'),
        'url' => ['/marketplace/list'],
    ]
];

$this->params['containerOptions'] = ['class' => 'container-fluid'];
$this->params['rowOptions'] = ['class' => 'row'];

$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.3.12/proj4-src.js');
$this->registerJs('Highcharts.maps["who/world"] = ' . file_get_contents(\Yii::getAlias('@app/data/countryPolygons/' . \prime\models\ar\Setting::get('countryPolygonsFile'))) . ';' .
    'Highcharts.maps["who/world"]["hc-transform"] = {default: {crs: "WGS84"}};'
);
//vdd((new \prime\models\mapLayers\Projects())->toArray());
$map = Highmaps::begin([
    'options' => [
        'title' => [
            'text' => false
        ],
        'mapNavigation' => [
            'enabled' => true,
            'buttonOptions' => [
                'verticalAlign' => 'bottom',
            ]
        ],
        'legend' => [
            'enabled' => true
        ],
        'plotOptions' => [
            'map' => [
                'allAreas' => false,
                'mapData' => new JsExpression('Highcharts.maps["who/world"]'),
            ]
        ],
        'series' => [
            (new \prime\models\MapLayer(['allAreas' => true, 'nullColor' => "rgba(255, 255, 255, 0)"]))->toArray(),
            (new \prime\models\mapLayers\Reports())->toArray(),
            (new \prime\models\mapLayers\Projects())->toArray()
        ],
        'credits' => [
            'enabled' => false
        ],
        'tooltip' => [
            'enabled' => false,
        ],
        'chart' => [
            'height' => 600,
            'backgroundColor' => null
        ]
    ],
    'htmlOptions' => [
        'style' => [
            'bottom' => '0px'
        ],
        'class' => [
            'col-xs-12',
            'col-md-12'
        ]
    ]
]);

$map->end();
echo \app\components\Html::tag('div', '', ['id' => 'map-details', 'class' => 'col-xs-0 col-md-0']);
$js = "
function select(point, layer) {
    $('#{$map->getId()}').removeClass('col-md-12').addClass('col-md-9');
    $('#{$map->getId()}').highcharts().reflow();
    $('#map-details').removeClass('col-xs-0').removeClass('col-md-0').addClass('col-md-3').addClass('col-xs-12');
    var id = point.id;
    $.ajax({
        url: '/marketplace/summary',
        data: {id: id, layer: layer}
    })
    .success(function(data) {
        $('#map-details').html(data);
    });

    //bootbox.alert(\"You selected \" + point.properties.CNTRY_TERR + \"!\");
}
";
$this->registerJs($js, $this::POS_BEGIN);
