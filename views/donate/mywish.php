<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$this->title = '我的心愿';
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
        1=>'成功',
        2=>'失败',
        3=>'已被删除',
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
                    <td>';
                       if($model['result'] != 3)echo $trans_status[$model['result']];
                    echo '</td>
                    <td>';
                    if($model['result'] != 3)
                    {
                        echo Html::a('修改', Url::to(['donate/editwish','id'=>$model['id']]),['class' => 'btn btn-success btn-xs']);
                        echo '&nbsp;&nbsp'.Html::a('详情', Url::to(['donate/wishdetail','id'=>$model['id']]),['class' => 'btn btn-success btn-xs']);
                        if($model['result']==2)
                        {
                            echo '&nbsp;&nbsp'.Html::a('再次申请', Url::to(['donate/updateapply','id'=>$model['id']]),['class' => 'btn btn-success btn-xs ']);
                        }
                    }
                    echo '</td>';
        echo '</tr>';
    }
}
function printtableend()
{
    echo '</tbody></table>';
}
    

printtalehead('申请者','总时间','金额','相关社区','日期','审核状态','握手状态');
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
