<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = '用户注册';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-login">
	<h1>请填写您的邮箱：</h1>
	<?php $form = ActiveForm::begin([
		'id' => 'register-form',
		'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],

	]);
	echo $form->field($model,'email');
	echo Html::submitButton('下一步',['class'=>'btn btn-primary']);

	ActiveForm::end();
	?>

</div>