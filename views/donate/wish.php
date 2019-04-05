<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;

$trans_name = array(
    'wish'=>'发布心愿',
    'editwish'=>'编辑心愿',
    );
$this->title = $trans_name[Yii::$app->controller->action->id];
$this->params['breadcrumbs'][] = ['label'=>'应用中心','url'=>\yii\helpers\Url::to(['site/appcenter'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-6\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-2 control-label'],
    ],
]); ?>
  
<div class="form-group field-wishform-count">
    <label class="col-lg-2 control-label" for="wishform-count">总时间(月)</label>
    <div class="col-lg-6">
    <select id="wishform-count" class="form-control" name="count">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
        <option value="9">9</option>
        <option value="10">10</option>
        <option value="11">11</option>
        <option value="12">一年</option>
        <option value="24">两年</option>
        <option value="36">三年</option>
        <option value="48">四年</option>
        <option value="60">五年</option>
        <option value="72">六年</option>
    </select>
    </div>
    <div class="col-lg-8"><p class="help-block help-block-error"></p></div>
</div>


<?php
    //echo $form->field($model, 'count')->dropDownList(['1' => '1', '2' => '2','3' => '3', '4' => '4','5' => '5', '6' => '6','7' => '7', '8' => '8','9' => '9', '10' => '10','11' => '11','12'=>'一年','24'=>'两年','36'=>'三年','48'=>'四年','60'=>'五年','72'=>'六年']);
    echo $form->field($model, 'totalMoney');

    if($this->title  == '发布心愿')
        {
			//echo $form->field($model, 'tag')->dropDownList(['1' => '贫困', '2' => '单亲','3' => '孤儿']);
            ?>

            <div class="form-group field-wishform-tag">
                    <label class="col-lg-2 control-label" for="wishform-tag">选择标签</label>
                <div class="col-lg-6">
                    <select id="wishform-tag" class="form-control" name="tag">
                        <option value="1">贫困</option>
                        <option value="2">单亲</option>
                        <option value="3">孤儿</option>
                    </select>
                </div>
                <div class="col-lg-8">
                    <p class="help-block help-block-error"></p>
                </div>
            </div>

            <?php
            $listData=ArrayHelper::map($allschool,'id','name');
            ?>
            <div class="form-group field-wishform-schoolid">
            <label class="col-lg-2 control-label" for="wishform-schoolid">选择社区</label>
            <div class="col-lg-6">
                <select id="wishform-schoolid" class="form-control" name="schoolid">
                    <option value="0">选择社区</option>
                    <?php foreach ($listData as $key => $value){echo '<option value="'.$key.'">'.$value.'</option>';} ?>
                </select>
            </div>
            <div class="col-lg-8"><p class="help-block help-block-error "></p></div>
            </div>
            <?php
            //echo $form->field($model, 'school')->dropDownList($listData,['prompt'=>'选择社区']);
            
            echo $form->field($model, 'schoolnumber');
            echo $form->field($model, 'guardian_name');
            echo $form->field($model, 'guardian_tel');
            echo $form->field($model, 'guardian_cardnumber');
    		echo  $form->field($model, 'description')->textarea(['rows'=>10,'style'=>'resize:none']);
        }
?>

    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-11">
            <?= Html::submitButton('确认', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>
    </div>

<?php ActiveForm::end();?>