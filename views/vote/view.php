<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\models\School;

$trans_name = array(
    'view'=>'投票详情',
    );
$this->title = $trans_name[Yii::$app->controller->action->id];
$this->params['breadcrumbs'][] = ['label'=>'应用中心','url'=>Url::to(['site/appcenter'])];
$this->params['breadcrumbs'][] = ['label'=>'我创建的团体','url'=>Url::to(['banji/mybanji'])];
$this->params['breadcrumbs'][] = ['label'=>'我的团体','url'=>Url::to(['banji/banjimates','id'=>$id_banji])];
$this->params['breadcrumbs'][] = $this->title;
?>
<h2><?=$this->title?></h2>

<h4 style="text-align:right">投票截止时间:&nbsp<?=$vote->endTime?></h4>
<h4 style="text-align:left">已投票数：<?=$count?> 总共可投：<?=$vote['Nmax']?> </h4>
<?php

$models = $wish;
//$models = $provider->getModels();
//$pages = $provider->getPagination();

function printtalehead($c1,$c2,$c3,$c4,$c5,$c6)
{
    echo '<table class="table table-hover">
            <thead>
            <tr>
                <th>'.$c1.'</th>
                <th>'.$c2.'</th>
                <th>'.$c3.'</th>
                <th>'.$c4.'</th>
                <th>'.$c5.'</th>
                <th>'.$c6.'</th>
                </tr>
        </thead>
         <tbody>';
}
function printtablebody($model,$title,$totalVotes,$vote,$count,$haveVoted)
{ 
    $class ='default';
    $percentage =round(($vote->result[$model['id']]/($totalVotes==0?1:$totalVotes))*100,2);//票数百分比,round()保留两位小数

    echo '<tr class="'.$class.'">
            <td>
                '.$model['toWho'].'
            </td>
            <td>
                '.$model['count'].'个月
            </td>
            <td>
                '.$model['totalMoney'].'
            </td>
            <td>
               '.$model['school'].'
            </td>
            <td>
               '.$model['wishtime'].'
            </td>
            <td style="width: 30%;">
                <div class="progress progress-striped active">
                    <div class="progress-bar progress-bar-success" role="progressbar" 
                        aria-valuenow='.$vote->result[$model['id']].' aria-valuemin="0" aria-valuemax='.$totalVotes.' style="width:'.$percentage.'%;">
                       <font color="#000" style="float:left;padding-left:10px">'.$vote->result[$model['id']].'</font>
                    </div>
                </div>
            </td>
            <td>
              '.Html::a('详情', Url::to(['donate/wishdetail','id'=>$model['id']]), ['class' => 'btn btn-success btn-sm']).'&nbsp';
             if($haveVoted)
             {
                echo '<i class="glyphicon glyphicon-ok">';
             }
             else if(!$haveVoted && $count < $vote->Nmax)
             {
                echo Html::a('给他一票', ['vote/vote','voteid'=>$vote->id,'wishid'=>$model['id']], ['class' => 'btn btn-success btn-sm']);
             }
             else
             {
                echo '<i class="glyphicon glyphicon-lock">';
             }
            echo '</td>
        </tr>';
}
function printtableend()
{
    echo '</tbody></table>';
}
    

printtalehead('申请者','总时间','金额','相关社区','申请时间','当前票数');
foreach ($models as $model)
{   
    $haveVoted = 0;
    if($vote->haveVoted != null && isset($vote->haveVoted[$model['id']]))
    {
        foreach($vote->haveVoted[$model['id']] as $v)
        {
            if($v == Yii::$app->user->identity->id)
            {
                $haveVoted = 1;
                break;
            }
        }
    }
    $model->toWho = User::findOne(['id'=>$model->toWho])->username;
    $model->school = School::findOne(['id'=>$model->school])->name;
    printtablebody($model,$this->title,$totalVotes,$vote,$count,$haveVoted);
}
printtableend();


/*echo yii\widgets\LinkPager::widget([
    'pagination' => $pages,
]);*/
?>
