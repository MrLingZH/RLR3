

<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = '发送邀请';
$this->params['breadcrumbs'][] = ['label'=>'应用中心','url'=>Url::to(['site/appcenter'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('succeed')): ?>
        <div class="alert alert-success">
            发送成功!&nbsp<a href=''>继续发送</a>
        </div>
    <?php else: ?>
        <?php if (Yii::$app->session->hasFlash('failed')): ?>
            <div class="alert alert-failed">
                发送失败!请检查邮箱地址是否有误！&nbsp<a href=''>重新发送</a>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-lg-3">

                    <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                        <?= $form->field($model, 'email') ?>

                        <div class="form-group">
                            <?= Html::submitButton('发送', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                        </div>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>