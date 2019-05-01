<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = '注册成功';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-login">
	<h1><?= Html::encode($this->title) ?></h1>
	<p>
		<?php
			if(Yii::$app->controller->action == 'register')
			{
				echo "恭喜你成功注册成为人恋人平台会员！";
			}
			else if(Yii::$app->controller->action == 'register_school')
			{
				echo "恭喜你成功注册成为人恋人平台见证人！";
			}
		?>
	</p>
	<?= Html::a('返回到首页',Url::to(['site/index']),['class'=>'btn btn-success']) ?>
	<?= Html::a('马上登录',Url::to(['site/login']),['class'=>'btn btn-success']) ?>
</div>