<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = '提交成功';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-login">
	<h1><?= Html::encode($this->title) ?></h1>
	<p>您的申请已成功提交！请确保在7天内提交相关的申请资料，否则视为放弃申请，审核不通过。审核结果将会通过邮件和站内消息通知您。</p>
	<?= Html::a('返回到首页',Url::to(['site/index']),['class'=>'btn btn-success']) ?>
</div>