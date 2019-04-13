<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = '修改成功';
$this->params['breadcrumbs'][] = ['label'=>'应用中心','url'=>\yii\helpers\Url::to(['site/appcenter'])];
$this->params['breadcrumbs'][] = ['label'=>'我的心愿','url'=>\yii\helpers\Url::to(['donate/mywish'])];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-login">
	<h1><?= Html::encode($this->title) ?></h1>
	<p>心愿已完成修改！请点击下列按钮继续操作。</p>
	<?= Html::a('回到列表',Url::to(['donate/mywish']),['class'=>'btn btn-success']) ?>
	<?= Html::a('查看详细',Url::to(['donate/wishdetail','id'=>$id]),['class'=>'btn btn-success']) ?>
</div>