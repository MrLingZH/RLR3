
<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->title = '资助详情';
$this->params['breadcrumbs'][] = ['label'=>'应用中心','url'=>\yii\helpers\Url::to(['site/appcenter'])];
//$this->params['breadcrumbs'][] = ['label'=>'我的心愿','url'=>\yii\helpers\Url::to(['donate/mywish'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<a href=<?=Yii::$app->request->getReferrer()?>>返回列表</a>
<?php
$trans_sex = [
	'man'=>'男',
	'woman'=>'女',
	null=>'未知',
];
$trans_status = [
    0=>'等待资助人',
    1=>'等待商议资助计划',
    2=>'投票进行中',
    3=>'资助进行中',
];
if(($wish['result'] == 1) && (Yii::$app->user->identity->degree == 'vip') && (Yii::$app->user->identity->username!=$toWho['username']) && ($wish['status']==0))
{
	echo '<h2>'.Html::a('我要帮助他', Url::to(['donate/donate','id'=>$wish['id']]),['class' => 'btn btn-primary', 'name' => 'view-button']).'</h2>';
}
else if((Yii::$app->user->identity->degree != 'auditor') && (Yii::$app->user->identity->degree == 'vip') && ($wish['result'] == 1))
{
	echo '<h2>状态: '.$trans_status[$wish['status']].'</h2>';
}
echo '<br>';
if(Yii::$app->user->identity->username == $wish->auditor && $wish->status == 1)
{
	echo Html::a('上传协议', Url::to(['donate/uploadprotocol','id'=>$wish['id']]),['class' => 'btn btn-primary', 'name' => 'view-button']);
}
if($wish->status == 1 || $wish->status == 3 || $wish->status == 4)
{
	echo '&nbsp'.Html::a('查看协议', Url::to(['donate/viewprotocol','id'=>$wish['id']]),['class' => 'btn btn-primary', 'name' => 'view-button']);
}
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<h4>
				<h4>资助时间线:</h4>
				<p><?=$data['progress']?></p>
				<p><br><br><br>时间线说明：绿色为已划账，红色为待划账</p>
				<?php
				if($wish['result'] == 2)
				{
					$reasonlabel = '拒绝理由';
				}
				if($wish['result'] == 1)
				{
					$reasonlabel = '同意理由';
				}
				else 
				{
					$reasonlabel = '见证人处理结果';
				}
				echo DetailView::widget([
				    'model' => $wish,
				    'attributes' => [
				    	['label'=>isset($wish['fromWho'])?'捐款人':isset($wish['fromClass'])?'捐款团体':'捐款人/团体','value'=>isset($wish['fromWho'])? $wish['fromWho']:isset($wish['fromClass'])?$wish['fromClass']:'无'],
				    	['label'=>isset($wish['fromWho'])?'捐款人电话':isset($wish['fromClass'])?'团体电话':'捐款人/团体电话','value'=>isset($wish['fromWho'])?'':isset($wish['fromClass'])?'':'无'],
				    	['label'=>isset($wish['fromWho'])?'捐款人Email':isset($wish['fromClass'])?'团体Email':'捐款人/团体Email','value'=>isset($wish['fromWho'])?'':isset($wish['fromClass'])?'':'无'],
				    	['label'=>'申请人','value'=>$toWho['username']],
				    	['label'=>'申请时间','value'=>$wish['wishtime']],
				    	[
		                'label'=>'头像',
		                'value'=> $toWho['headimage'],
		                'format' => ['image',['width'=>'100','alt'=>"用户未上传"]],
        				],
        				['label'=>'电话','value'=>$toWho['tel']],
        				[
                        'label'=>'身份证正面',
                        'value'=> $toWho['idcard_upside'],
                        'format' => ['image',['width'=>'400','alt'=>"用户未上传"]],
				        ],
				        [
                        'label'=>'身份证反面',
                        'value'=> $toWho['idcard_downside'],
                        'format' => ['image',['width'=>'400','alt'=>"用户未上传"]],
    					],
        				['label'=>'监护人','value'=>$wish['guardian_name']],
        				['label'=>'监护人电话','value'=>$wish['guardian_tel']],
        				['label'=>'监护人卡号','value'=>$wish['guardian_cardnumber']],
        				//['label'=>'付款周期','value'=>$wish['donateinterval'].'个月'],
        				['label'=>'付款周期','value'=>'1个月'],
        				['label'=>'性别','value'=>$trans_sex[$toWho['sex']]],
        				['label'=>'邮箱','value'=>$toWho['email']],
				    	['label'=>'总金额','value'=>$wish['totalMoney'].'元人民币'],
				       	['label'=>'申请总时间','value'=>$wish['count'].'个月'],
				       	['label'=>'审核人','value'=>$wish['auditor']],
				        ['label'=>$reasonlabel,'value'=>$wish['reason'],'format'=>'raw'],
				        ['label'=>'申请人详述','value'=>$wish['description'],'format'=>'raw'],
					],
				]);
				?>
			</h4>
		</div>
	</div>
</div>
<?php
//var_dump($toWho);
//var_dump($wish);
?>