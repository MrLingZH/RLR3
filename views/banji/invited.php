<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = '已发出的邀请';
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
function printtablebody($column1,$column2,$column3,$column4)
{
    $trans_result = [
        -1=>'已拒绝',
        0=>'等待处理',
        1=>'已接受',
    ];
    if($column4<0)
    {
        echo '<tr class="">
                        <td>
                            '.$column1.'
                        </td>
                        <td>
                        '.$column2.'
                        </td>
                        <td>
                        '.$column3.'
                        </td>
                        <td><span style="color:red">
                           '.$trans_result[$column4].'
                        </span></td>
                    </tr>';
    }
    else
    {
         echo '<tr class="">
                        <td>
                            '.$column1.'
                        </td>
                        <td>
                        '.$column2.'
                        </td>
                        <td>
                        '.$column3.'
                        </td>
                        <td><span style="color:green">
                           '.$trans_result[$column4].'
                        </span></td>
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
    printtalehead('时间','用户名','邮箱','状态');
    foreach ($models as $model) 
    {
        printtablebody($model['sendTime'],$model['toWho'],$model['email'],$model['result']);
    }
    printtableend();
echo yii\widgets\LinkPager::widget([
    'pagination' => $pages,]);
?>