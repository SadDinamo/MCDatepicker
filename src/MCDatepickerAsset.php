<?php
/**
*
*
*
 */
namespace saddinamo\mcdatepicker;

use yii\web\AssetBundle;

/**
 * Class McdatepickerAsset
 *
 */
class McdatepickerAsset extends AssetBundle
{
    public $sourcePath = '@bower/highcharts-release/';

    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public $js = [
        'mc-calendar.min.js',
    ];
}