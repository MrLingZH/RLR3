<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\School;
use app\models\User;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model app\models\School */
/* @var $form ActiveForm */
$this->title = '社区详情';
$this->params['breadcrumbs'][] = ['label'=>'应用中心','url'=>\yii\helpers\Url::to(['site/appcenter'])];
$this->params['breadcrumbs'][] = $school['name'];

?>
<div class="school-view">

<h1><?= Html::encode($school['name']) ?></h1>
<?php
$schoolAdmin = User::findOne(['id'=>$school['witnessid']]);
if(Yii::$app->user->identity->school == $schoolAdmin->id)
{
	echo Html::a('完善社区资料', ['school/updatebywitness'], ['class' => 'btn btn-success btn-sm']).'&nbsp'; 
	echo Html::a('设置资金比例', ['school/setminpercent'], ['class' => 'btn btn-success btn-sm']).'&nbsp'; 
	echo Html::a('设置首页图片', ['school/upload_banner_image'], ['class' => 'btn btn-success btn-sm']); 
}
else if(Yii::$app->user->identity->isAdmin())
{
    echo Html::a('修改社区资料', ['school/update','id'=>$school['id']], ['class' => 'btn btn-success btn-sm']);
}
?>
<br><br>
<?=DetailView::widget([
    'model' => $school,
    'attributes' => [
    	'name',
    	['label'=>'二级域名','value'=>$school['subDomain'],'format'=>['url',['target'=>'_blank']]],
    	'address',
    	'type',
        ['label'=>'社区管理员','value'=>$schoolAdmin->username],
        ['label'=>'管理员邮箱','value'=>$schoolAdmin->email],
        ['label'=>'管理员电话','value'=>$schoolAdmin->tel],
    ],
])?>
</div><!-- school-create -->
