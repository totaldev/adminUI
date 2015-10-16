<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\adminUi\widgets\Header;
use yii\adminUi\widgets\Nav;
use yii\adminUi\widgets\NavBar;
use yii\adminUi\widgets\NavBarUser;
use yii\adminUi\widgets\NavBarMessage;
use yii\adminUi\widgets\NavBarNotification;
use yii\adminUi\widgets\NavBarTask;
use yii\adminUi\widgets\Breadcrumbs;

/**
 * @var \yii\web\View $this
 * @var string        $content
 */
AppAsset::register($this);
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
<div class="wrapper">
    <?php
    $this->beginBody();
    Header::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl'   => Yii::$app->homeUrl,
        'options'    => [
            'tag'   => 'header',
            'class' => 'main-header',
        ],
    ]);
    NavBar::begin([
        'options' => [
            'class' => 'navbar navbar-static-top',
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

    <aside class="main-sidebar">
        <section class="sidebar">
            <?=Nav::widget([
                'header' => mb_convert_case(Yii::t('app', 'Main navigation'), MB_CASE_UPPER),
                'headerOptions' => [
                    'class' => 'header'
                ],
                'options' => [
                    'class' => 'sidebar-menu'
                ],
                'items' => isset(Yii::$app->menu) ? Yii::$app->menu : [],
                'activateParents' => true
            ]);
            ?>
        </section>
    </aside>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <?php echo $this->title; ?>
                <small>
                    <?php
                    if (isset($this->params['pagelabel'])) {
                        echo $this->params['pagelabel'];
                    } else { ?>
                        <?=Yii::t('app', 'Control panel')?>
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
        <section class="content">
            <?= $content ?>
        </section>

    </div>
    <footer class="main-footer">
        <div class="pull-right hidden-xs"><?= Yii::powered() ?></div>
        <p>&copy; My Company <?= date('Y') ?></p>
    </footer>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
