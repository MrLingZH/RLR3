<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title = '填写注册信息';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-login">
	<h1><?= Html::encode($this->title) ?></h1>
	<p>我们已经验证码发送至注册邮箱，请登录注册的邮箱获取验证码继续完成注册。</p>
	<?php $form = ActiveForm::begin([
		'id' => 'register-form',
		'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],

	]);
	echo $form->field($model,'code');
	echo $form->field($model,'username');
	echo $form->field($model,'password')->passwordInput();
	echo $form->field($model,'repassword')->passwordInput();
	echo $form->field($model,'tel');
	$listData=ArrayHelper::map($allschool,'id','name');//把查询到的数据$allschool截成'id'=>'name'
	if(count($allschool)==1)
    {
        $model->schoolid = $allschool[0]->id;
    }
	echo $form->field($model, 'schoolid')->dropDownList(
                                        $listData, 
                                        ['prompt'=>'选择社区']);
	echo $form->field($model, 'acknowledgement')->checkbox([
            'template' => '<div class=\"col-lg-offset-2 col-lg-3\">{input} 同意
            <a href='.Url::to(['site/about']).' target="blank">《人恋人社区须知》</a>
            </div><div class=\"col-lg-8\">{error}</div>',
        ]);
	echo Html::submitButton('完成',['class'=>'btn btn-success']);

	ActiveForm::end();
	?>

</div>