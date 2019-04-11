<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = '注册学校';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-login">
	<p>提示：该项需提交相关资料且需要审核。</p>
	<?= Html::a('请务必点击此处查看具体申请流程',Url::to(['site/about'])) ?>
	<?php $form = ActiveForm::begin([
		'id' => 'register-form',
		'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],

	]);
	echo $form->field($model,'name');
	echo Html::submitButton('提交',['class'=>'btn btn-primary']);

	ActiveForm::end();

	?>

</div>