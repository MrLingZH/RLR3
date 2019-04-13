<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

use yii\helpers\ArrayHelper;
$trans_name = array(
    'mydonation'=>'我的握手',
    'mywish'=>'我的心愿',
    'donatesupplylist'=>'心愿申请',
    'donatesupplydonelist'=>'握手列表',
    'addinvote'=>'选择投票对象',
    'needdonatelist'=>'需要握手的人',
    'continue'=>'继续选择握手对象',
    'donatelistcalledfromvote'=>'选择投票对象',
    'lastonehundreddonate'=>'最近资助成功记录',
    'lastonehundredapply'=>'最近公益信息记录',
    );
$this->title = $trans_name[Yii::$app->controller->action->id];
$this->params['breadcrumbs'][] = ['label'=>'应用中心','url'=>\yii\helpers\Url::to(['site/appcenter'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<h2><?=$this->title?></h2>
<?php
$models = $provider->getModels();
$pages = $provider->getPagination();

function printtalehead($column1,$column2,$column3,$column4)
{
    echo '<table class="table table-hover">
                    <thead>
                    <tr>
                        <th>
                            '.$column1.'
                        </th>
                        <th>
                            '.$column2.'
                        </th>
                        <th>
                           '.$column3.'
                        </th>
                         <th>
                           '.$column4.'
                        </th>
                        </tr>
                </thead>
                 <tbody>';
}
function printtablebody($model,$title,$vote)
{
    $trans_result = [
        0=>'等待处理',
        1=>'成功',
        2=>'失败',
    ];
    $trans_status = [
        0=>'等待资助人',
        1=>'申请已同意',
        2=>'申请已拒绝',
    ];
    if(($model['result'] != 0)||(Yii::$app->user->identity->username == $model['toWho']))
    {
        switch ($model['result']) {
            case 1:
                $class = 'success';
            break;
            case 2:
                $class ='info';
                $model['status'] = 2;
            break;
            case 0:
                $class ='default';
            break;
            default:
                $class ='default';
            break;
        }
        
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
                        <td>
                           '.$trans_result[$model['result']].'
                        </td>
                        <td>
                           '.$trans_status[$model['status']].'
                        </td>
                        <td>';
                        if(($title=='选择投票对象')||($title=='继续选择握手对象'))
                        {
                           echo Html::a('加入投票', Url::to(['vote/addinvote','donateid'=>$model['id'],'voteid'=>$vote->id]),['class' => 'btn btn-success btn-xs']);
                        }
                        else if($title=='需要握手的人')
                        {
                           echo Html::a('握手', Url::to(['donate/donate','id'=>$model['id']]),['class' => 'btn btn-success btn-xs']);
                        }
                        else if((Yii::$app->user->identity->username==$model['toWho'] || (Yii::$app->user->identity->username==$model['auditor'])) && ($model['status']==0))
                        {
                            echo Html::a('修改', Url::to(['donate/editwish','id'=>$model['id']]),['class' => 'btn btn-success btn-xs']);
                        } 
                        else if((Yii::$app->user->identity->username==$model['auditor'])&&($model['status']=='等待商议握手计划'||$model['status']=='等待商议资助计划'))
                        {
                            echo Html::a('握手计划', Url::to(['donate/editdonate','id'=>$model['id']]),['class' => 'btn btn-success btn-xs']).'&nbsp';
                            echo Html::a('开始执行', Url::to(['donate/updatedonatemodel','id'=>$model['id'],'status'=>'in_progress']),['class' => 'btn btn-success btn-xs']).'&nbsp';
                        }
                        else if($title=='我的心愿' && $model['result']=='disagreed')
                        {
                            echo Html::a('再次申请', Url::to(['donate/updateapply','id'=>$model['id']]),['class' => 'btn btn-success btn-xs ']);
                            echo '&nbsp;&nbsp'.Html::a('修改', Url::to(['donate/editwish','id'=>$model['id']]),['class' => 'btn btn-success btn-xs ']);
                        }
                        echo '&nbsp;&nbsp'.Html::a('详情', Url::to(['donate/wishdetail','id'=>$model['id']]),['class' => 'btn btn-success btn-xs']);
                        echo '</td>
                        </tr>';
    }
    else if(Yii::$app->user->identity->username==$model['auditor'])
    {    //安全起见，判断一下这个donate的审核人是不是当前用户
        $class = 'default';
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
                           '.$model['applytime']->format('Y-m-d H:i:s').' 
                        </td>
                        <td>
                            '.Html::a('修改', Url::to(['donate/editdonate','id'=>$model['id']]),['class' => 'btn btn-success btn-xs']).'
                            '.Html::a('详情', Url::to(['donate/viewdonate','id'=>$model['id']]),['class' => 'btn btn-success btn-xs']).'
                        </td>
                        <td>
                            '.Html::a('同意', Url::to(['donate/agreedonate','id'=>$model['id']]),['class' => 'btn btn-success btn-xs']).'
                            '.Html::a('拒绝', Url::to(['donate/disagreedonate','id'=>$model['id']]),['class' => 'btn btn-warning btn-xs']).'
                            '.Html::a('删除', Url::to(['donate/deletedonate','id'=>$model['id']]),['class' => 'btn btn-danger btn-xs']).'
                        </td>
                    </tr>';
    }
    
}
function printtableend()
{
    echo '</tbody></table>';
}
    

printtalehead('申请者','总时间','金额','相关社区','日期');
foreach ($models as $model) 
{
            printtablebody(
                $model,
                $this->title,
                isset($vote)? $vote:''
            );
}
printtableend();

echo yii\widgets\LinkPager::widget([
    'pagination' => $pages,
]);
//var_dump($models);
?>
