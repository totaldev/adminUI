<?php
use backend\assets\AppAsset;
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
            <?php
            echo NavBarUser::Widget(['type' => 'sidebar']);

            $menuItems = [Yii::t('app','Main navigation')];
            if (isset(Yii::$app->menu)) {
                $menuItems = Yii::$app->menu->items;
            }

            echo Nav::widget([
                'options' => ['class' => 'sidebar-menu'],
                'items'   => $menuItems,
            ]);
            ?>
        </section>
    </aside>

    <aside class="right-side">
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
        <section class="content">
            <?= $content ?>
        </section>

    </aside>
    <footer class="main-footer">
        <div class="container">
            <div class="pull-right hidden-xs"><?= Yii::powered() ?></div>
            <p class="pull-left">&copy; My Company <?= date('Y') ?></p>
        </div>
    </footer>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
