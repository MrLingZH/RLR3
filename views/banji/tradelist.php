<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = '充值记录';
$this->params['breadcrumbs'][] = ['label'=>'应用中心','url'=>\yii\helpers\Url::to(['site/dashboard'])];
$this->params['breadcrumbs'][] = ['label'=>'我创建的团体','url'=>\yii\helpers\Url::to(['banji/mybanji'])];
$this->params['breadcrumbs'][] = ['label'=>'我的团体','url'=>\yii\helpers\Url::to(['banji/banjimates','id'=>$banjiid])];
$this->params['breadcrumbs'][] = $this->title;
?>


<?php
function printtalehead($column1,$column2,$column3,$column4)
{
    echo '<table class="table table-hover">
                <thead>
                <tr>
                    <th>'.$column1.'</th>
                    <th>'.$column2.'</th>
                    <th>'.$column3.'</th>
                    <th>'.$column4.'</th>
                </tr>
                </thead>
                <tbody>';
}
function printtablebody($column1,$column2,$column3,$column4,$column5,$class)
{
    $trans_type = [
        1=>'转账',
        2=>'充值',
        3=>'提现',
    ];
    $trans_status = [
        -1=>'失败',
        0=>'待确认',
        1=>'成功',
    ];
    if($column4<0)
    {
        echo '<tr class="'.$class.'">
                        <td>
                            '.$column2.'
                        </td>
                        <td>
                        '.$trans_type[$column3].'
                        </td>
                        <td><span style="color:red">
                           '.$column4.'
                        </span></td>
                        <td>
                        '.$trans_status[$column5].'
                        </td>
                        <td>
                            '.Html::a('详情', Url::to(['money/view','id'=>$column1])).'
                        </td>
                    </tr>';
    }
    else
    {
         echo '<tr class="'.$class.'">
                        <td>
                            '.$column2.'
                        </td>
                        <td>
                        '.$trans_type[$column3].'
                        </td>
                        <td><span style="color:green">
                           +'.$column4.'
                        </span></td>
                        <td>
                        '.$trans_status[$column5].'
                        </td>
                        <td>
                            '.Html::a('详情', Url::to(['money/view','id'=>$column1])).'
                        </td>
                    </tr>';
    }
}
function printtableend()
{
    echo '</tbody></table>';
}
    $models = $provider->getModels();
    $pages = $provider->getPagination();
    echo '<h3>最近交易记录</h3>';
    printtalehead('时间','说明','金额','交易状态');
    foreach ($models as $model) 
    {
        printtablebody($model['id'],$model['tradeTime'],$model['type'],$model['money'],$model['status'],'');
    }
    printtableend();
echo yii\widgets\LinkPager::widget([
    'pagination' => $pages,]);
?>