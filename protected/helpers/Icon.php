<?php


namespace prime\helpers;


use prime\assets\IconBundle;
use yii\helpers\Html;
use yii\helpers\Inflector;

/**
 * Class Icon
 * @package prime\helpers
 * @method static string eye(array $options = [])
 * @method static string pencilAlt(array $options = [])
 * @method static string share(array $options = [])
 * @method static string hospital(array $options = [])
 * @method static string user(array $options = [])
 * @method static string clipboardList(array $options = [])
 * @method static string signOutAlt(array $options = [])
 * @method static string windowMaximize(array $options = [])
 * @method static string admin(array $options = [])
 *
 * // NovelT icons
 * @method static string project(array $options = [])
 * @method static string healthFacility(array $options = [])
 * @method static string delete(array $options = [])
 * @method static string edit(array $options = [])
 * @method static string download(array $options = [])
 * @method static string sync(array$options = [])
 */
class Icon
{

    public static function contributors(array $options = []): string
    {
        return self::partner($options);
    }

    public static function icon($name, array $options = [])
    {

        Html::addCssClass($options, ['icon', "icon-$name"]);
        return Html::tag('svg',
           self::svgSource($name),
            $options
        );
    }

    public static function svgSource($name) {
        $svg = IconBundle::register(\Yii::$app->view)->baseUrl . '/symbol-defs.svg';
        return Html::tag('use', '', ['href' => "{$svg}#icon-{$name}"]);
    }

    public static function __callStatic($name, $arguments)
    {
        return self::icon(Inflector::camel2id($name), $arguments[0] ?? []);
    }
}