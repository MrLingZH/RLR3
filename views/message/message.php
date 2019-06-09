
<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use app\models\Invite;

$this->title = '站内信';
$this->params['breadcrumbs'][] = ['label'=>'应用中心','url'=>\yii\helpers\Url::to(['site/appcenter'])];
$this->params['breadcrumbs'][] = ['label'=>'收件箱','url'=>\yii\helpers\Url::to(['message/index'])];
$this->params['breadcrumbs'][] = '站内信';
?>
<h2>站内信</h2>
<a href=<?=Url::to(['message/index'])?>>返回列表</a>
<div class="box-message">
	<div class="box-message-title">
		<?=$message['fromWho'];?>
	</div>
	<div class="box-message-body">
		&nbsp&nbsp&nbsp&nbsp<?=$message['content'];?>

		<?php
		//判断是否为邀请信
		if($invite = Invite::findOne(['id'=>$message['invite']]))
		{
			echo '<div class="box-message-deal">';
			switch($invite->result)
			{
				case -1:
					echo '<font style="color:#f33">已拒绝该请求</font>';
					break;
				case 0:
					echo Html::a('接受',Url::to(['message/request','inviteid'=>$invite->id,'messageid'=>$message['id'],'type'=>'banjiinvite','deal'=>'accept']),['class'=>'box-message-but']);
					echo Html::a('拒绝',Url::to(['message/request','inviteid'=>$invite->id,'messageid'=>$message['id'],'type'=>'banjiinvite','deal'=>'refuse']),['class'=>'box-message-but']);
					break;
				case 1:
					echo '<font style="color:#3f3">已接受该请求</font>';
					break;
			}
			echo '</div>';
		}
		?>

	</div>
	<div class="box-message-end">
		<?=$message['sendTime']?>
	</div>
</div>