<?php
declare(strict_types=1);

use prime\models\ar\Permission;
use SamIT\abac\interfaces\PermissionRepository;
use SamIT\abac\repositories\CachedReadRepository;
use SamIT\abac\repositories\PreloadingSourceRepository;
use SamIT\Yii2\abac\ActiveRecordRepository;
use yii\di\Container;

return [
    PermissionRepository::class => PreloadingSourceRepository::class,
    PreloadingSourceRepository::class => function(Container $container) {
        return new PreloadingSourceRepository($container->get(CachedReadRepository::class));
    },
    CachedReadRepository::class => function(Container $container) {
        return new CachedReadRepository($container->get(ActiveRecordRepository::class));
    },
    ActiveRecordRepository::class => function() {
        return new ActiveRecordRepository(Permission::class, [
            ActiveRecordRepository::SOURCE_ID => ActiveRecordRepository::SOURCE_ID,
            ActiveRecordRepository::SOURCE_NAME => 'source',
            ActiveRecordRepository::TARGET_ID => ActiveRecordRepository::TARGET_ID,
            ActiveRecordRepository::TARGET_NAME => 'target',
            ActiveRecordRepository::PERMISSION => ActiveRecordRepository::PERMISSION
        ]);
    },
    \kartik\grid\ActionColumn::class => static function (Container $container, array $params = [], array $config = []): \yii\grid\ActionColumn {
        if (!isset($config['header'])) {
            $config['header'] = \Yii::t('app', 'Actions');
        }
        $result = new \kartik\grid\ActionColumn($config);
        return $result;
    },
    \kartik\switchinput\SwitchInput::class => static function (Container $container, array $params, array $config) {
        $config = \yii\helpers\ArrayHelper::merge([
            'pluginOptions' => [
                'offText' => \Yii::t('app', 'Off'),
                'onText' => \Yii::t('app', 'On'),
            ]
        ], $config);
        return new \kartik\switchinput\SwitchInput($config);
    },
    \kartik\grid\GridView::class => [
        'export' => false
    ],


];
