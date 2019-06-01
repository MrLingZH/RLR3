<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

$trans_title = [
	'forgot'=>'找回密码',
	'reset_psw'=>'重设密码',
];
$this->title = $trans_title[Yii::$app->controller->action->id];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-login">
	<h1><?= Html::encode($this->title) ?></h1>
	<p>通过邮箱验证<?= $trans_title[Yii::$app->controller->action->id] ?></p>
	<?php if(Yii::$app->session->hasFlash('Succeed')): ?>
	修改密码成功！
	<br/>
	<?= Html::a('马上登录',Url::to(['site/login']),['class'=>'btn btn-success']) ?>
	<?php else:
	$form = ActiveForm::begin([
		'id' => 'register-form',
		'layout' => 'horizontal',
	    'fieldConfig' => [
	        'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
	        'labelOptions' => ['class' => 'col-lg-1 control-label'],
    ],

	]);
	echo $form->field($model,'email');
	echo "<spand id='but-get-verify-code'>获取验证码</spand>&nbsp&nbsp&nbsp<font id='font-get-verify-code'></font>";
	echo "<div>&nbsp</div>";
	echo $form->field($model,'code');
	echo $form->field($model,'password')->passwordInput();
	echo $form->field($model,'repassword')->passwordInput();
	echo Html::submitButton('完成',['class'=>'btn btn-success']);
	ActiveForm::end();


    $js = <<<EOF
    var input_email = $('#forgotform-email');
    var t;//计时器
    var s = 0;//秒数
    var isSend = false;
    $('#but-get-verify-code').click(function(){
    	if((s == 0) && (!isSend))
    	{
    		var reg = new RegExp("^[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$");		//正则表达式
	    	if(input_email.val() == '')
	    	{
	    		$('#font-get-verify-code').text('请填写邮箱');
	    		$('#font-get-verify-code').css({color: '#f00'});
	    	}
	    	else if(!reg.test(input_email.val()))		//reg.test()执行验证，符合表达式返回true，否者返回false。
	    	{
	    		$('#font-get-verify-code').text('邮箱格式错误');
	    		$('#font-get-verify-code').css({color: '#f00'});
	    	}
	    	else
	    	{
	    		$('#but-get-verify-code').text('发送中');
	    		$('#but-get-verify-code').css({
					background: '#d8d8d8',
					color: '#707070',
				});
				isSend = true;
				$.ajax({
					'type':'POST',
					'url':'index.php?r=site/get_verify_code&type=forgot',
					'data':{
						'email':input_email.val(),
					},
					'dataType':'json',
					'success':function(data){
						switch(data.status)
						{
							case 0:
								$('#font-get-verify-code').text('获取成功！请前往邮箱查看并填写验证码。');
								$('#font-get-verify-code').css({color: '#2f2'});
								isSend = false;
								s = 60;
								$('#but-get-verify-code').text(s+'秒后可重新获取');
								$('#but-get-verify-code').css({
									background: '#d8d8d8',
                					color: '#707070',
								});
								t = setTimeout(timer,1000);
								break;
							case 1:
								$('#font-get-verify-code').text('该邮箱用户不存在。');
								$('#font-get-verify-code').css({color: '#f00'});
								$('#but-get-verify-code').text('获取验证码');
								$('#but-get-verify-code').css({
									background: '',
									color: '',
								});
								isSend = false;
								break;
							case 2:
								$('#font-get-verify-code').text('邮件发送失败！请检查邮箱地址是否有效。');
								$('#font-get-verify-code').css({color: '#f00'});
								$('#but-get-verify-code').text('获取验证码');
								$('#but-get-verify-code').css({
									background: '',
									color: '',
								});
								isSend = false;
								break;
							default:
								alert('未知错误');
								$('#but-get-verify-code').text('获取验证码');
								$('#but-get-verify-code').css({
									background: '',
									color: '',
								});
								isSend = false;
								break;
						}
					},
					'error':function(data){
						alert('错误：'+data.status);
						$('#but-get-verify-code').text('获取验证码');
						$('#but-get-verify-code').css({
							background: '',
							color: '',
						});
						isSend = false;
					},
		    	});
	    	}
    	}
    });

    function timer()
    {
		if(s > 0)
		{
			s -= 1;
			$('#but-get-verify-code').text(s+'秒后可重新获取');
			t = setTimeout(timer,1000);
		}
		else
		{
			$('#but-get-verify-code').text('获取验证码');
			$('#but-get-verify-code').css({
				background: '#50aa50',
				color: '#fff',
			});
		}
    }

EOF;
	$this->registerJs($js);
	endif;
	?>

</div>