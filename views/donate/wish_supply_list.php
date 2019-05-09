<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

use yii\helpers\ArrayHelper;

$this->title = $title0;
$this->params['breadcrumbs'][] = ['label'=>'应用中心','url'=>\yii\helpers\Url::to(['site/appcenter'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<h2><?=$this->title?></h2>
<?php
$models = $provider->getModels();
$pages = $provider->getPagination();

function printtalehead($column1,$column2,$column3,$column4,$column5,$column6,$column7)
{
    echo '<table class="table table-hover">
                    <thead>
                    <tr>
                        <th>'.$column1.'</th>
                        <th>'.$column2.'</th>
                        <th>'.$column3.'</th>
                        <th>'.$column4.'</th>
                        <th>'.$column5.'</th>
                        <th>'.$column6.'</th>
                        <th>'.$column7.'</th>
                        </tr>
                </thead>
                 <tbody>';
}
function printtablebody($model,$title,$vote)
{
    $trans_result = [
        0=>'等待处理',
        1=>'通过',
        2=>'拒绝',
        3=>'已删除',
    ];
    $trans_status = [
        0=>'等待资助人',
        1=>'等待商议资助计划',
        2=>'进行投票中',
        3=>'资助进行中',
        4=>'资助完成',
        5=>'逾期',
    ];
    if(($model['result']!=0)||(Yii::$app->user->identity->username==$model['toWho']))
    {
        switch ($model['result']) {
            case 1:
                $class = 'success';
            break;
            case 2:
                $class ='info';
                //$model['status'] = '申请已拒绝';
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
                        echo Html::a('详情', Url::to(['donate/wishdetail','id'=>$model['id']]),['class' => 'btn btn-success btn-xs']);
                        echo '&nbsp;&nbsp';
                        if(($title=='选择投票对象') || ($title=='继续选择握手对象'))
                        {
                           echo Html::a('加入投票', Url::to(['vote/addinvote','donateid'=>$model['id'],'voteid'=>$vote->id]),['class' => 'btn btn-success btn-xs']);
                        }
                        else if($title=='资助他人')
                        {
                           echo Html::a('握手', Url::to(['donate/donate','id'=>$model['id']]),['class' => 'btn btn-success btn-xs']);
                        }
                        else if((Yii::$app->user->identity->username==$model['toWho'] || (Yii::$app->user->identity->id==$model['auditor'])) && ($model['result'] == 0))
                        {
                            echo Html::a('修改', Url::to(['donate/editwish','id'=>$model['id']]),['class' => 'btn btn-success btn-xs']);
                        } 
                        else if(($model['status']==1) && (Yii::$app->user->identity->degree=='witness'))
                        {
                            echo Html::a('握手计划', Url::to(['donate/editwish','id'=>$model['id']]),['class' => 'btn btn-success btn-xs']).'&nbsp';
                            echo Html::a('开始执行', Url::to(['donate/update_wish_status','id'=>$model['id'],'status'=>'3']),['class' => 'btn btn-success btn-xs']).'&nbsp';
                        }
                        else if($title=='我的心愿' && $model['result']==2)
                        {
                            echo Html::a('修改', Url::to(['donate/editdonate','id'=>$model['id']]),['class' => 'btn btn-success btn-xs ']);
                            echo '&nbsp;&nbsp'.Html::a('再次申请', Url::to(['donate/updateapply','id'=>$model['id']]),['class' => 'btn btn-success btn-xs ']);
                        }
                        echo '</td>
                        </tr>';
    }
    else if(Yii::$app->user->identity->id==$model['auditor'])
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
                           '.$model['applytime'].' 
                        </td>
                        <td>
                            '.Html::a('修改', Url::to(['donate/editwish','id'=>$model['id']]),['class' => 'btn btn-success btn-xs']).'
                            '.Html::a('详情', Url::to(['donate/wishdetail','id'=>$model['id']]),['class' => 'btn btn-success btn-xs']).'
                        </td>
                        <td>
                            '.Html::a('同意', Url::to(['donate/wish_agreed','id'=>$model['id']]),['class' => 'btn btn-success btn-xs']).'
                            '.Html::a('拒绝', Url::to(['donate/wish_disagreed','id'=>$model['id']]),['class' => 'btn btn-warning btn-xs']).'
                            '.Html::a('删除', Url::to(['donate/wish_delete','id'=>$model['id']]),['class' => 'btn btn-danger btn-xs']).'
                        </td>
                    </tr>';
    }
    
}
function printtableend()
{
    echo '</tbody></table>';
}
    

printtalehead('申请者','总时间','金额','相关社区','日期','审核状态','资助状态');


foreach ($models as $model) 
{
            printtablebody(
            $model,$this->title,
            isset($vote)? $vote:'');
}
printtableend();

echo yii\widgets\LinkPager::widget([
    'pagination' => $pages,
]);
?>
