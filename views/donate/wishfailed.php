<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = '注册失败';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-login">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php
	switch($status)
	{
		case 1:
			echo "<p>该邮箱已被注册。</p>";
			break;
		case 2:
			echo "<p>邮箱验证码发送失败！请使用有效的邮箱注册！</p>";
			break;
		default:
			echo "<p>注册失败。</p>";
			break;
	}
	?>
	<?= Html::a('返回',Url::to(['site/register']),['class'=>'btn btn-success']) ?>
</div>