<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = '许愿成功';
$this->params['breadcrumbs'][] = ['label'=>'应用中心','url'=>\yii\helpers\Url::to(['site/appcenter'])];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-login">
	<h1><?= Html::encode($this->title) ?></h1>
	<p>恭喜成功许下心愿，请耐心等待愿望实现！</p>
	<?= Html::a('返回到应用中心',Url::to(['site/appcenter']),['class'=>'btn btn-success']) ?>
	<?= var_dump($data) ?>
</div>