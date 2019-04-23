<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = '操作失败';
$this->params['breadcrumbs'][] = ['label'=>'应用中心','url'=>\yii\helpers\Url::to(['site/appcenter'])];
$this->params['breadcrumbs'][] = ['label'=>'心愿申请','url'=>\yii\helpers\Url::to(['donate/list_apply'])];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-login">
	<h1><?= Html::encode($this->title) ?></h1>
	<p>状态：<?= $status ?></p>
	<?= Html::a('返回到应用中心',Url::to(['site/appcenter']),['class'=>'btn btn-success']) ?>
</div>