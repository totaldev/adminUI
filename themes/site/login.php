<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="login-box">
    <div class="login-logo">
        <a href="/"><?=$this->title?></a>
    </div><!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg"><?=Yii::t('app', 'Sign in to start your session')?></p>
        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

        <?= $form->field($model, 'username') ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <?= $form->field($model, 'rememberMe')->checkbox() ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>

        <a href="<?=\yii\helpers\Url::to(['/site/forgot-password'])?>"><?=Yii::t('app', 'I forgot my password')?></a><br>
        <a href="<?=\yii\helpers\Url::to(['/site/register'])?>" class="text-center"><?=Yii::t('app', 'Register a new membership')?></a>

    </div><!-- /.login-box-body -->
</div><!-- /.login-box -->
