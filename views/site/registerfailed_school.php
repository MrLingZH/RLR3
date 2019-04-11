<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = '申请失败';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-login">
	<h1><?= Html::encode($this->title) ?></h1>
	<?php
	switch($status)
	{
		case 1:
			echo "<p>错误：01</p>";
			break;
		case 2:
			echo "<p>错误：02</p>";
			break;
		case 3:
			echo "<p>您已经是学校见证人。</p>";
			break;
		default:
			echo "<p>申请失败。</p>";
			break;
	}
	?>
	<?= Html::a('返回',Url::to(['site/register']),['class'=>'btn btn-success']) ?>
</div>