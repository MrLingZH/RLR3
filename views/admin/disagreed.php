<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = '拒绝';
$this->params['breadcrumbs'][] = ['label'=>'应用中心','url'=>\yii\helpers\Url::to(['site/appcenter'])];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-login">
	<h1>请填写拒绝理由</h1>
	<?php $form = ActiveForm::begin([
		'id' => 'register-form',
		'layout' => 'horizontal',
        'fieldConfig' => [
            //'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'template' => "<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],

	]);
	echo $form->field($model,'reson')->textarea(['rows'=>10,'style'=>'resize:none']);
	echo Html::submitButton('完成',['class'=>'btn btn-primary']);

	ActiveForm::end();
	?>

</div>