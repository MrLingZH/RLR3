<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = '收件箱';
$this->params['breadcrumbs'][] = ['label'=>'应用中心','url'=>\yii\helpers\Url::to(['site/appcenter'])];
$this->params['breadcrumbs'][] = '收件箱';
?>
<h2>收件箱</h2>
<?php

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
function printtablebody($column1,$column2,$column3,$column4,$column5,$class)
{
    $trans_status = [
        0=>'未读',
        1=>'已读',
    ];
    if($column4 == 1)
    {
        echo '<tr class="'.$class.'">
                        <td>
                            '.$column2.'
                        </td>
                        <td>
                        '.Html::a($column3, Url::to(['message/message','id'=>$column1])).'
                        </td>
                        <td><span style="color:#000">
                           '.$trans_status[$column4].'
                        </span></td>
                        <td>
                            '.$column5.'
                        </td>
                        <td>
                            '.Html::a('查看', Url::to(['message/message','id'=>$column1,])).'
                            '.Html::a('标记为未读', Url::to(['message/set_unreaded','id'=>$column1,])).'
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
                        '.Html::a($column3, Url::to(['message/message','id'=>$column1])).'
                        </td>
                        <td><span style="color:red">
                           '.$trans_status[$column4].'
                        </span></td>
                        <td>
                            '.$column5.'
                        </td>
                        <td>
                            '.Html::a('查看', Url::to(['message/message','id'=>$column1])).'
                            '.Html::a('标记为已读', Url::to(['message/set_readed','id'=>$column1])).'
                        </td>
                    </tr>';
    }
}
function printtableend()
{
    echo '</tbody></table>';
}
echo '<h3>个人</h3>';
    $models = $provider->getModels();
    $pages = $provider->getPagination();
    printtalehead('发件人','标题','状态','日期');
    foreach ($models as $model) 
    {
        printtablebody($model['id'],$model['fromWho'],$model['title'],$model['status'],$model['sendTime'],'');
    }
    printtableend();
echo yii\widgets\LinkPager::widget([
    'pagination' => $pages,]);
?>
