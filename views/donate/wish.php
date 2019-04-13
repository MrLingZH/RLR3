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
if($this->title == '编辑心愿')$this->params['breadcrumbs'][] = ['label'=>'我的心愿','url'=>\yii\helpers\Url::to(['donate/mywish'])];
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
  
<?php
    echo $form->field($model, 'count')->dropDownList(['1' => '1', '2' => '2','3' => '3', '4' => '4','5' => '5', '6' => '6','7' => '7', '8' => '8','9' => '9', '10' => '10','11' => '11','12'=>'一年','24'=>'两年','36'=>'三年','48'=>'四年','60'=>'五年','72'=>'六年']);
    echo $form->field($model, 'totalMoney');

    if($this->title  == '发布心愿' || 1)
        {
			echo $form->field($model, 'tag')->dropDownList(['1' => '贫困', '2' => '单亲','3' => '孤儿']);
            $listData=ArrayHelper::map($allschool,'id','name');
            echo $form->field($model, 'schoolid')->dropDownList($listData,['prompt'=>'选择社区']);
            
            echo $form->field($model, 'schoolnumber');
            echo $form->field($model, 'guardian_name');
            echo $form->field($model, 'guardian_tel');
            echo $form->field($model, 'guardian_cardnumber');
    		echo $form->field($model, 'description')->textarea(['rows'=>10,'style'=>'resize:none']);
        }
?>

    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-11">
            <?= Html::submitButton('确认', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>
    </div>

<?php ActiveForm::end();?>