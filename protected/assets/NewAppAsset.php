<?php


namespace prime\assets;


use yii\web\AssetBundle;

class NewAppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/main.css',
    ];

    public $js = [
    ];

    public $depends = [
        IconBundle::class,
        SourceSansProBundle::class,
        FormBundle::class
    ];
}