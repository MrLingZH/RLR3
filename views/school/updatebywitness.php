<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use \yii\helpers\Url;

$this->title = '更新学校信息';
$this->params['breadcrumbs'][] = ['label'=>'应用中心','url'=>Url::to(['site/appcenter'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (Yii::$app->session->hasFlash('RegistSchoolFormSubmitted')): ?>
        <div class="alert alert-success">
            Thank you for Registering.</a>
        </div>
    <?php else: ?>
    <?php 
        $form = ActiveForm::begin([
        'id' => 'login-form',
        //'enableAjaxValidation' => true,
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>
        <?= $form->field($model, 'address') ?>
        <?= $form->field($model, 'type') ?>
        <?= $form->field($model, 'subDomain') ?>
         <?='' //$form->field($model, 'verifyCode')->widget(Captcha::className(), [
             //           'template' => '<div class="row"><div class="col-lg-5">{image}</div><div class="col-lg-6">{input}</div></div>',
             //       ]) 
         ?>
        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('修改', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
    <?php endif; ?>
</div>
