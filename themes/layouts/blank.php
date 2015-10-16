<?php
use backend\assets\AppAsset;
use yii\adminUi\assetsBundle\AdminUiAsset;
use yii\helpers\Html;
use yii\adminUi\widgets\Header;
use yii\adminUi\widgets\Nav;
use yii\adminUi\widgets\NavBar;
use yii\adminUi\widgets\NavBarUser;
use yii\adminUi\widgets\NavBarMessage;
use yii\adminUi\widgets\NavBarNotification;
use yii\adminUi\widgets\NavBarTask;
use yii\widgets\Breadcrumbs;

/**
 * @var \yii\web\View $this
 * @var string        $content
 */
AppAsset::register($this);
AdminUiAsset::register($this);
$this->beginPage()
?><!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <title><?= Html::encode($this->title) ?></title>
    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
</head>
<body class="bg-black">
<?php $this->beginBody(); ?>
<?= $content ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
