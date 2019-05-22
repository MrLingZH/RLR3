<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DatePicker;

$this->title = '发起团体投票';
$this->params['breadcrumbs'][] = ['label'=>'应用中心','url'=>\yii\helpers\Url::to(['site/appcenter'])];
$this->params['breadcrumbs'][] = ['label'=>'我创建的团体','url'=>\yii\helpers\Url::to(['banji/mybanji'])];
$this->params['breadcrumbs'][] = ['label'=>'我的团体','url'=>\yii\helpers\Url::to(['banji/banjimates','id'=>$id_banji])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-6\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <?= $form->field($vote, 'title')?>
    <?= $form->field($vote, 'Nmax')?>
    <?= $form->field($vote, 'endTime')->widget(
    DatePicker::className(), [
        // inline too, not bad
        'inline' => true, 
        // modify template for custom rendering
        'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
        'clientOptions' => [
            'autoclose' => true,
            'language'=>'en',
            'todayHighlight' => true,
            'format' => 'yyyy-mm-dd',
        ]
    ]);?>
    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('确认', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
