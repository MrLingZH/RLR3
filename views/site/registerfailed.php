<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = '注册失败';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-login">
	<h1><?= Html::encode($this->title) ?></h1>
	<p>该邮箱已被注册。</p>
	<?= Html::a('返回',Url::to(['site/register']),['class'=>'btn btn-success']) ?>
</div>