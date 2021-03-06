<?php
/**
 * @link      http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license   http://www.yiiframework.com/license/
 */

namespace yii\adminUi\assetsBundle;

use yii\web\AssetBundle;

/**
 * Asset bundle for the Twitter bootstrap javascript files.
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since  2.0
 */
class AdminUiPluginAsset extends AssetBundle {
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'yii\adminUi\assetsBundle\AdminUiAsset',
    ];
}
