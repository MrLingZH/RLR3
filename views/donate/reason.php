<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$trans_title = [
	'wish_agreed'=>'同意',
	'wish_disagreed'=>'拒绝',
	'wish_delete'=>'删除',
];
$this->title = $trans_title[Yii::$app->controller->action->id];
$this->params['breadcrumbs'][] = ['label'=>'应用中心','url'=>\yii\helpers\Url::to(['site/appcenter'])];
$this->params['breadcrumbs'][] = ['label'=>'心愿申请','url'=>\yii\helpers\Url::to(['donate/list_apply'])];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-login">
	<h1>请填写<?=$trans_title[Yii::$app->controller->action->id]?>理由</h1>
	<?php $form = ActiveForm::begin([
		'id' => 'register-form',
		'layout' => 'horizontal',
        'fieldConfig' => [
            //'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'template' => "<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],

	]);
	echo $form->field($model,'reason')->textarea(['rows'=>10,'style'=>'resize:none']);
	echo Html::submitButton('完成',['class'=>'btn btn-primary']);

	ActiveForm::end();
	?>

</div>