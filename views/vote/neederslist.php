<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

use yii\helpers\ArrayHelper;

$this->title = '选择投票对象';
$this->params['breadcrumbs'][] = ['label'=>'应用中心','url'=>\yii\helpers\Url::to(['site/appcenter'])];
$this->params['breadcrumbs'][] = ['label'=>'我创建的团体','url'=>\yii\helpers\Url::to(['banji/mybanji'])];
$this->params['breadcrumbs'][] = ['label'=>'我的团体','url'=>\yii\helpers\Url::to(['banji/banjimates','id'=>$id_banji])];
$this->params['breadcrumbs'][] = $this->title;
?>
<h2><?=$this->title?></h2>
<?php
$models = $provider->getModels();
$pages = $provider->getPagination();

function printtalehead($column1,$column2,$column3,$column4,$column5)
{
    echo '<div class="neederslist">
            <table class="table table-hover">
            <thead>
            <tr>
                <th>'.$column1.'</th>
                <th>'.$column2.'</th>
                <th>'.$column3.'</th>
                <th>'.$column4.'</th>
                <th>'.$column5.'</th>
            </tr>
            </thead>
            <tbody>';
}
function printtablebody($model,$voteid)
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
    $class ='default';
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
            <td>';
            echo Html::a('加入投票', Url::to(['vote/addinvote','wishid'=>$model['id'],'voteid'=>$voteid]),['id'=>'addinvote','class' => 'btn btn-success btn-xs']);
            echo '&nbsp;&nbsp';
            echo Html::a('详情', Url::to(['donate/wishdetail','id'=>$model['id']]),['class' => 'btn btn-success btn-xs']);        
            echo '</td>
            </tr>';
        }
function printtableend()
{
    echo '</tbody></table></div>';
}

function printtalehead2($column1)
{
    echo '<div class="neederslist_checked">
            <table class="table table-hover">
            <thead>
            <tr>
                <th>'.$column1.'</th>
            </tr>
            </thead>
            <tbody>';
}
function printtablebody2($model,$voteid)
{
    $class ='default';
    echo '<tr class="'.$class.'">
            <td>
                '.$model['toWho'].'
            </td>
            <td>';
            echo Html::a('取消选择', Url::to(['vote/checked_cancel','wishid'=>$model['id'],'voteid'=>$voteid]),['class' => 'btn btn-success btn-xs']);
            echo '&nbsp;&nbsp';
            echo Html::a('详情', Url::to(['donate/wishdetail','id'=>$model['id']]),['class' => 'btn btn-success btn-xs']);        
            echo '</td>
            </tr>';
}   
function printtableend2()
{
    echo '</tbody></table></div>';
}

printtalehead('申请者','总时间','金额','相关社区','日期');
    

foreach ($models as $model) 
{
    printtablebody($model,$voteid);
}

echo yii\widgets\LinkPager::widget([
    'pagination' => $pages,
]);
printtableend();
?>
<h2>已选择的投票对象(<?= $count_vote_checked.'/'.($voteNmax+1) ?>)</h2>
<?php
printtalehead2('申请者');

foreach ($wish_checked as $model) 
{
    printtablebody2($model,$voteid);
}

printtableend2();

$voteNmax1 = $voteNmax + 1;
$js = <<<JS
    $('a[id="addinvote"]').click(function()
    {
        if($count_vote_checked < $voteNmax1)
        {
             return true;
        }
        else
        {
             alert("已选对象已达到上限。");
             return false;
        }
    });
JS;

$this->registerJs($js);
//preg_match("/r\=[A-Za-z0-9]+\%2F+[A-Za-z0-9]*/",$url_back,$r);//匹配正则表达式赋给$r(数组)
//var_dump($r);
//echo $url_back;
?>
