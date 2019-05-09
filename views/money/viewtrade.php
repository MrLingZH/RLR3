
<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = '交易详情';
$this->params['breadcrumbs'][] = ['label'=>'应用中心','url'=>\yii\helpers\Url::to(['site/appcenter'])];
$this->params['breadcrumbs'][] = '交易详情';
?>
<h2>交易详情</h2>
<a href=<?=Yii::$app->request->getReferrer()?>>返回</a>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			
			<?php
			$trans_type = [
				1=>'转账',
				2=>'充值',
				3=>'提现',
			];
			echo '<h2>
				交易类型:'.$trans_type[$tradeinfo['type']].'
			</h2>';
			echo '<h2>
				金额:'.$tradeinfo['money'].'
			</h2>';
			echo '<h2>
				流水号:'.$tradeinfo['transaction_id'].'
			</h2>';
			switch ($tradeinfo['type'])
			{
				case 1:
					if($tradeinfo['fromWho'] == null)
					{
						echo '<h2>转账团体:'.$tradeinfo['fromClass'].'</h2>';
					}
					else
					{
						echo '<h2>转账者:'.$tradeinfo['fromWho'].'</h2>';
					}
					if($tradeinfo['toWho'] == null)
					{
						echo '<h2>接受团体:'.$tradeinfo['toClass'].'</h2>';
					}
					else
					{
						echo '<h2>接受者:'.$tradeinfo['toWho'].'</h2>';
					}
					break;
				case 2:
					echo '<h2>充值者:'.$tradeinfo['toWho'].'</h2>';
					break;
				case 3:
					echo '<h2>提现者:'.$tradeinfo['toWho'].'</h2>';
					//echo '<h2>'.$tradeinfo['type'].'银行:'.$tradeinfo['bankName'].'</h2>';
					//echo '<h2>'.$tradeinfo['type'].'卡号:'.$tradeinfo['cardNumber'].'</h2>';
					//echo '<h2>持卡人:'.$tradeinfo['holderName'].'</h2>';
					break;
				default:
					# code...
					break;
			}
			?>

			<h2>
				交易时间:<?=$tradeinfo['tradeTime'];?>
			</h2>
		</div>
	</div>
</div>