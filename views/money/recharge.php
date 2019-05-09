<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = '充值';
$this->params['breadcrumbs'][] = ['label'=>'应用中心','url'=>\yii\helpers\Url::to(['site/appcenter'])];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-login">
	<h1>请填写充值金额</h1>
	<?php $form = ActiveForm::begin([
		'id' => 'recharge-form',
		'layout' => 'horizontal',
        'fieldConfig' => [
            //'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'template' => "<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],

	]);
	echo $form->field($model,'money');
	?>
	选择支付方式:
	<input type="radio" name="way" value="2" checked="checked">支付宝&nbsp&nbsp&nbsp
	<input type="radio" name="way" value="3">微信支付
	<br/>
	<?php
	echo Html::submitButton('提交',['class'=>'btn btn-primary']);

	ActiveForm::end();
	?>

</div>
<?php
$js = <<<EOF
$('#simpleform-money').keydown(function(event){
	if(event.keyCode == 13)
	{
		$('#recharge-form').submit();
	}
	if($('#simpleform-money').val() == '')
	{
		if(event.keyCode == 48 || event.keyCode == 96)
		{
			return false;
		}
	}
	if(!((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105) || event.keyCode == 8 || event.keyCode == 9))
	{
		return false;
	}
});
EOF;
$this->registerJs($js);
?>