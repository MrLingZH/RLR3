<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = '创建失败';
$this->params['breadcrumbs'][] = ['label'=>'应用中心','url'=>\yii\helpers\Url::to(['site/appcenter'])];
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
		default:
			echo "<p>创建失败</p>";
			break;
	}
	?>
	<?= Html::a('返回',Url::to(['banji/create']),['class'=>'btn btn-success']) ?>
</div>