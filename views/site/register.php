<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

$trand_title = [
	'register'=>'会员注册',
	'register_school'=>'见证人注册',
];

$this->title = $trand_title[Yii::$app->controller->action->id];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-login">
	<h1><?= Html::encode($this->title) ?></h1>
	<p>请填写注册表单</p>
	<?php $form = ActiveForm::begin([
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
	echo $form->field($model,'username');
	echo $form->field($model,'password')->passwordInput();
	echo $form->field($model,'repassword')->passwordInput();
	echo $form->field($model,'tel');
	if(Yii::$app->controller->action->id == 'register')
	{
		$listData=ArrayHelper::map($allschool,'id','name');//把查询到的数据$allschool截成'id'=>'name'
		echo $form->field($model, 'schoolid')->dropDownList(
	                                        $listData, 
	                                        ['prompt'=>'选择社区']);
		echo $form->field($model,'schoolnumber');
		$schoolnumber = [];
	    $schoolid = [];
	    foreach($allschool as $value)
	    {
	        $schoolnumber[$value->id] = $value->schoolnumber;
	        $schoolid[$value->schoolnumber] = $value->id;
	    }
	    $js1 = 'var schoolnumber = '.json_encode($schoolnumber).';
            var schoolid = '.json_encode($schoolid).';';
	    $js2 = <<<EOF
	    $('#registerform-schoolid').change(function(){
	        $('#registerform-schoolnumber').val(schoolnumber[$(this).val()]);
	    });
	    $('#registerform-schoolnumber').change(function(){
	        if(!schoolid[$(this).val()])
	        {
	            alert('无效的社区代码');
	            $('#registerform-schoolid').val('');
	            $('#registerform-schoolnumber').val('');
	        }
	        else
	        {
	            $('#registerform-schoolid').val(schoolid[$(this).val()]);
	        }
	    });
EOF;
	    $js3 = "var input_email = $('#registerform-email');";
	    $this->registerJs($js1);
	    $this->registerJs($js2);
	}
	else
	{
		echo $form->field($model,'schoolname');
		$js3 = "var input_email = $('#registerform_school-email');";
	}
	echo $form->field($model, 'acknowledgement')->checkbox([
            'template' => '<div class=\"col-lg-offset-2 col-lg-3\">{input} 同意
            <a href='.Url::to(['site/about']).' target="blank">《人恋人社区须知》</a>
            </div><div class=\"col-lg-8\">{error}</div>',
        ]);
	echo Html::submitButton('完成',['class'=>'btn btn-success']);
	ActiveForm::end();


    $js4 = <<<EOF
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
					'url':'index.php?r=site/get_verify_code',
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
								$('#font-get-verify-code').text('邮箱已被注册');
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

    $this->registerJs($js3);
    $this->registerJs($js4);
	?>

</div>

<script type="text/javascript">

</script>