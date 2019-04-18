
<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = '站内信';
$this->params['breadcrumbs'][] = ['label'=>'应用中心','url'=>\yii\helpers\Url::to(['site/appcenter'])];
$this->params['breadcrumbs'][] = ['label'=>'收件箱','url'=>\yii\helpers\Url::to(['message/index'])];
$this->params['breadcrumbs'][] = '站内信';
?>
<h2>站内信</h2>
<a href=<?=Url::to(['message/index'])?>>返回列表</a>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<h2>
				<font style="font-weight:bold;font-style:italic;"><?=$message['fromWho'];?>:</font>
			</h2>
			<h2>
				&nbsp&nbsp&nbsp&nbsp<?=$message['content'];?>
			</h2>
			<font style="font-weight:bold;font-style:italic;"><?=$message['sendTime']?></font>
		</div>
	</div>
</div>