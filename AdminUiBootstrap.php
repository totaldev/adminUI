<?php
namespace yii\adminUi;

use Yii;
use yii\base\BootstrapInterface;

class AdminUiBootstrap implements BootstrapInterface {

    public function bootstrap($app) {
        $app->set('view', [
            'class' => 'yii\web\View',
            'theme' => [
                'pathMap' => ['@backend/views' => '@backend/themes/adminui'],
                // for Admin theme which resides on extension/adminui
                //'baseUrl' => '@web/themes/adminui',
            ],
        ]);

        $app->set('assetManager', [
            'class'      => 'yii\web\AssetManager',
            'bundles'    => [
                'backend\assets\AppAsset' => [
                    'depends' => [
                        'yii\adminUi\assetsBundle\AdminUiAsset'
                    ],
                ],
            ],
            'linkAssets' => true,
        ]);
    }
}
