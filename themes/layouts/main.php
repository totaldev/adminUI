<?php
use backend\assets\AppAsset;
use yii\UrlAsset\component\UrlAsset;
use yii\helpers\Html;
use yii\adminUi\widget\Header;
use yii\adminUi\widget\Nav;
use yii\adminUi\widget\NavBar;
use yii\adminUi\widget\NavBarUser;
use yii\adminUi\widget\NavBarMessage;
use yii\adminUi\widget\NavBarNotification;
use yii\adminUi\widget\NavBarTask;
use yii\adminUi\widget\Breadcrumbs;

/**
 * @var \yii\web\View $this
 * @var string        $content
 */
AppAsset::register($this);
$urls = new UrlAsset();
$urls->registerAll($this);
$urls->setParams($this);
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
<body class="skin-blue">
<?php
$this->beginBody();
Header::begin([
    'brandLabel' => 'My Company',
    'brandLabel' => Yii::$app->name,
    'brandUrl'   => Yii::$app->homeUrl,
    'options'    => [
        'tag'   => 'header',
        'class' => 'header',
    ],
]);
NavBar::begin([
    'options' => [
        'class' => 'navbar-static-top',
    ],
]);

$menuItems = [];
if (Yii::$app->user->isGuest) {
    $menuItems[] = ['content' => NavBarUser::Widget(), 'options' => ['class' => '']];
} else {
    $menuItems[] = ['content' => NavBarMessage::Widget(), 'options' => ['class' => 'dropdown messages-menu']];
    $menuItems[] = ['content' => NavBarNotification::Widget(), 'options' => ['class' => 'dropdown notifications-menu']];
    $menuItems[] = ['content' => NavBarTask::Widget(), 'options' => ['class' => 'dropdown tasks-menu']];
    $menuItems[] = ['content' => NavBarUser::Widget(), 'options' => ['class' => 'dropdown user user-menu']];
}

echo Nav::widget([
    'options' => ['class' => 'nav navbar-nav'],
    'items'   => $menuItems,
]);
NavBar::end();
Header::end();
?>
<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="left-side sidebar-offcanvas">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <?php
            echo NavBarUser::Widget(['type' => 'sidebar']);

            if (isset(Yii::$app->menu)) {
                $menuitems = Yii::$app->menu->items;
            } else {
                $menuItems = [];
            }

            echo Nav::widget([
                'options' => ['class' => 'sidebar-menu'],
                'items'   => $menuitems,
            ]);
            ?>
        </section>
    </aside>

    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <?php echo $this->title; ?>
                <small>
                    <?php
                    if (isset($this->params['pagelabel'])) {
                        echo $this->params['pagelabel'];
                    } else { ?>
                        Control panel
                    <?php } ?>
                </small>
            </h1>
            <?php
            echo Breadcrumbs::widget([
                'tag'     => 'ol',
                'options' => ['class' => 'breadcrumb'],
                'links'   => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
        </section>

        <!-- Main content -->
        <section class="content">
            <?= $content ?>
        </section>
        <!-- /.content -->
    </aside>
    <!-- /.right-side -->
</div>
<!-- ./wrapper -->

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
