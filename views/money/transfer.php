<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
$url = Url::to(['site/getuserdata']);
$str = '确认对方账户信息：';
$js = <<<JS
// get the form id and set the event
$('#transfer-form').on('beforeValidate', function(e) {
    var form = $(this);
    var username =$('input[name="SimpleForm[toWho]"]').val();
    var mount = $('input[name="SimpleForm[money]"]').val();
    var result=$.ajax({
        type: "GET",
        url: 'index.php?r=site/getuserdata&username='+username,
        async: false
    }).responseText;
    result_obj= JSON.parse(result);
    if(result_obj!=null)
    {
        if(confirm("$str"+result_obj['username']+" 电话:"+result_obj['tel']+" 邮箱:"+result_obj['email']+"?")) {
            return true;
        } 
    }
    else
    {
        alert("请正确输入对方用户id");
    }
    return false;
})
JS;
if(Yii::$app->controller->action->id =='transfertoperson') {
    $this->registerJs($js);
}
$trans_name = array(
    'transfertoclass'=>'转账给团体',
    'transfertoperson'=>'转账给个人',
    );
$this->title = $trans_name[Yii::$app->controller->action->id];
$this->params['breadcrumbs'][] = ['label'=>'应用中心','url'=>Url::to(['site/appcenter'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>
 <div class="row">
            <div class="col-lg-10">
    <?php $form = ActiveForm::begin([
        'id' => 'transfer-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

<?php
switch ($this->title) {
    case '转账给团体':
        echo $form->field($model, 'money');
        break;
    case '转账给个人':
        echo $form->field($model, 'money');
        echo $form->field($model, 'toWho');
    break;
    default:
        # code...
        break;
}
?>
        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-11">
                <?= Html::submitButton('确认', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
</div>
</div>    
</div>
