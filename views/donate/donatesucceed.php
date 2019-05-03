<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = '握手成功';
$this->params['breadcrumbs'][] = ['label'=>'应用中心','url'=>\yii\helpers\Url::to(['site/appcenter'])];
$this->params['breadcrumbs'][] = ['label'=>'资助他人','url'=>\yii\helpers\Url::to(['donate/wish_supply_list','status'=>0,'result'=>1])];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-login">
	<h1><?= Html::encode($this->title) ?></h1>
	<p>恭喜你与<?= $toWho ?>握手成功！请在“我的资助”中查看资助进程。</p>
	<?= Html::a('返回到应用中心',Url::to(['site/appcenter']),['class'=>'btn btn-success']) ?>
	<?= Html::a('返回到资助他人',Url::to(['donate/wish_supply_list','status'=>0,'result'=>1]),['class'=>'btn btn-success']) ?>
</div>