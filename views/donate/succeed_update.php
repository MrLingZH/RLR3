<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = '操作成功';
$this->params['breadcrumbs'][] = ['label'=>'应用中心','url'=>\yii\helpers\Url::to(['site/appcenter'])];
switch(Yii::$app->controller->action->id)
{
	case 'update_wish_status':
		$a = Html::a('继续操作',Url::to(['donate/wish_supply_list','status'=>1,'result'=>1]),['class'=>'btn btn-success']);
		$this->params['breadcrumbs'][] = ['label'=>'待定资助周期','url'=>\yii\helpers\Url::to(['donate/wish_supply_list','status'=>1,'result'=>1])];
		break;
	
	default:
		$a = Html::a('继续操作',Url::to(['site/appcenter']),['class'=>'btn btn-success']);
		break;
}
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-login">
	<h1><?= Html::encode($this->title) ?></h1>
	<p></p>
	<?= $a ?>
</div>