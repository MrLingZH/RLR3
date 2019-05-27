<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Url;

$trans_name = array(
    'uploadheadimage'=>'上传头像',
    'uploadcertificate'=>'上传协议书',
    );
$this->title = $trans_name[Yii::$app->controller->action->id];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (Yii::$app->session->hasFlash('PictureUploaded')): ?>
        <div class="alert alert-success">
            感谢您的提交</a>
        </div>
    <?php else: ?>
    <?php 
        $form = ActiveForm::begin([
        'id' => 'login-form',
        //'enableAjaxValidation' => true,
        'options' => ['class' => 'form-horizontal','enctype'=>'multipart/form-data'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>
        <?= $form->field($model, 'file')->fileInput()->label('选择文件') ?>
        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('提交', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
    <?php endif; ?>
</div>
