

<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = '发送私信';
$this->params['breadcrumbs'][] = ['label'=>'应用中心','url'=>\yii\helpers\Url::to(['site/appcenter'])];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('Succeed')): ?>
        <div class="alert alert-success">发送成功!</div>
        <?= Html::a('返回应用中心',Url::to(['site/appcenter']),['class'=>'btn btn-primary']) ?>
        <?= Html::a('再写一封',Url::to(['message/write']),['class'=>'btn btn-primary']) ?>
    <?php else: ?>

    <?php if (Yii::$app->session->hasFlash('Failed')): ?>
        <div class="alert alert-failed">发送失败!</div>
        <?= Html::a('返回应用中心',Url::to(['site/appcenter']),['class'=>'btn btn-primary']) ?>
        <?= Html::a('重新编辑',Url::to(['message/write']),['class'=>'btn btn-primary']) ?>
    <?php else: ?>

        <div class="row">
            <div class="col-lg-10">

                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                    <?= $form->field($model, 'toWho') ?>

                    <?= $form->field($model, 'title') ?>

                    <?= $form->field($model, 'content')->textarea(['rows'=>10,'style'=>'resize:none']);
                    ?>
                   <div class="form-group">
                        <?= Html::submitButton('发送', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>

            </div>
            <div class="col-lg-2 tongxl">
                <div class="fill-lian">通讯录</div>
                <div class="zjlx">
                    <?php
                        $trans_degree = [
                            'vip'=>'会员',
                            'admin'=>'管理员',
                        ];
                        if(count($contacts)>0)
                        {
                            foreach ($contacts as $vo)
                            {
                    ?>
                                <div u="<?= $vo['username'] ?>" style="cursor: pointer;" onclick="selectUser(this)"><?= $vo['username'].'/'.$trans_degree[$vo['degree']] ?></div>
                <?php
                            }
                ?>
                <?php
                        }
                ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php endif; ?>
</div>

<script type="text/javascript">
    function selectUser(el)
    {

        var user = el.getAttribute('u');
        document.getElementById("messageform-towho").value = user;
    }
</script>