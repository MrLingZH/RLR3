<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;
use app\models\User;

$this->title = '我的团体';
$this->params['breadcrumbs'][] = ['label'=>'应用中心','url'=>Url::to(['site/appcenter'])];
/*$url_back = Yii::$app->request->getReferrer();
//echo $url_back;
$title_back = '上一级';
if($url_back == 'http://localhost/rlr3/web/index.php?r=banji%2Fmybanji')
{
    $title_back = '我创建的团体';
}
else if($url_back == 'http://localhost/rlr3/web/index.php?r=banji%2Fbanjiincludeme')
{
    $title_back = '我加入的团体';
}
$this->params['breadcrumbs'][] = ['label'=>$title_back,'url'=>$url_back];*/
$this->params['breadcrumbs'][] = ['label'=>'我创建的团体','url'=>Url::to(['banji/mybanji'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1>成员列表</h1>
<h3>
    团体余额：<?=$banji['money']?>元
</h3>

<?= Html::a('充值', Url::to(['money/transfertoclass','id'=>$banji['id'],]),['class' => 'btn btn-primary  btn-sm', 'name' => 'view-button']) ?>
&nbsp
<?= Html::a('团体流水', Url::to(['class/tradelist','id'=>$banji['id'],]),['class' => 'btn btn-primary  btn-sm', 'name' => 'view-button']) ?>
&nbsp
<?= Html::a('邀请成员', Url::to(['class/invite','id'=>$banji['id'],]),['class' => 'btn btn-primary  btn-sm', 'name' => 'register-button'])?>
&nbsp

<?php
if($banji['administrator']==Yii::$app->user->identity->username)
{

    echo Html::a('发起团体投票', Url::to(['vote/launchvote','id'=>$banji['id'],]),['class' => 'btn btn-primary btn-sm', 'name' => 'register-button']);
    
    echo '<h3>正在计划的投票活动</h3>';
    if($allNotCompleteVotes == null)
    {
        echo '<font color="#fff" style="background-color:#cde;padding:5px;border-radius:3px">空空如也</font>';
    }
    else
    {
        echo '<div class="box_banjimates_table">';
        printvotetablehead('投票主题','状态','截止时间');
        foreach ($allNotCompleteVotes as $model) 
        {
            printVoteNotCompleteTableBody($model['title'],$model['status'],$model['endTime'],$model['id'],'');
        }
        printvotetableend();
        echo '</div>';
    }
}

echo '<h3>正在执行的投票活动</h3>';
if($beginVote == null)
{
    echo '<font color="#fff" style="background-color:#cde;padding:5px;border-radius:3px">空空如也</font>';
}
else
{
    echo '<div class="box_banjimates_table">';
    printvotetablehead('投票主题','状态','截止时间');
    foreach ($beginVote as $vote) 
    {
        printvotetablebody($vote,$banji,'');
    }
    printvotetableend();
    echo '</div>';
}

echo '<h3>正在帮助</h3>';
if($donateByUs == null)
{
    echo '<font color="#fff" style="background-color:#cde;padding:5px;border-radius:3px">空空如也</font>';
}
else
{
    echo '<div class="box_banjimates_table">';
    printhelptablehead('姓名','总金额','状态','已捐期数');
    foreach ($donateByUs as $donatemodel) 
    {
        printhelptablebody($donatemodel,'');
    }
    printvotetableend();
    echo '</div>';
}

echo '<h3>逾期项目</h3>';
if($donateByUsOverDue == null)
{
    echo '<font color="#fff" style="background-color:#cde;padding:5px;border-radius:3px">空空如也</font>';
}
else
{
    echo '<div class="box_banjimates_table">';
    printhelptablehead('姓名','总金额','已捐期数');
    foreach ($donateByUsOverDue as $donatemodel) 
    {
        printhelptablebody($donatemodel,'');
    }
    printvotetableend();
    echo '</div>';
}

$models = $provider->getModels();
echo '<h3>同学录</h3>';
echo '<div class="box_banjimates_table">';
printclassmatestablehead('用户名','邮箱','');
foreach ($models as $model) 
{
    printclassmatestablebody($model['username'],$model['email'],'');
}
printclassmatestableend();
echo '</div>';

function printclassmatestablehead($column1,$column2)
{
    echo '<table class="table">
                    <thead>
                    <tr>
                        <th>
                            '.$column1.'
                        </th>
                        <th>
                            '.$column2.'
                        </th>
                    </tr>
                </thead>
                 <tbody>';
}

function printclassmatestablebody($column1,$column2,$class)
{
      echo '<tr class="'.$class.'">
                        <td>
                            '.$column1.'
                        </td>
                        <td>
                            '.$column2.'
                        </td>
                     </tr>';
}
function printclassmatestableend()
{
    echo '</tbody></table>';
}

function printvotetablehead($title,$status,$endTime)
{
echo '<table class="table">
                    <thead>
                    <tr>
                        <th>
                            '.$title.'
                        </th>
                        <th>
                            '.$status.'
                        </th>
                        <th>
                            '.$endTime.'
                        </th>
                    </tr>
                </thead>
                 <tbody>';
}
function printVoteNotCompleteTableBody($title,$status,$endTime,$voteid,$class)
{
    $trans_status = [0=>'计划中',1=>'投票进行中',2=>'投票已结束',3=>'投票已删除',4=>'等待重新投票'];
    echo '<tr class="'.$class.'">
            <td>
                '.$title.'
            </td>
            <td>
                '.$trans_status[$status].'
            </td>
            <td>
                '.date('Y-m-d',strtotime($endTime)).'
            </td>
            <td>';
                echo Html::a('编辑候选人', ['vote/editneeders','id'=>$voteid], ['class' => 'btn btn-success btn-sm']).'&nbsp';
                echo Html::a('开始投票', ['vote/beginvote','id'=>$voteid], ['class' => 'btn btn-success btn-sm']).'&nbsp';
                echo Html::a('删除', ['vote/delete','id'=>$voteid], ['id'=>'but_vote_delete','class' => 'btn btn-danger btn-sm']);
                echo '</td>
            </th>
        </tr>';
}
function printvotetablebody($vote,$banji,$class)
{
    $trans_status = [0=>'计划中',1=>'投票进行中',2=>'投票已结束',3=>'投票已删除',4=>'等待重新投票'];
    if($vote['isReset']==true)
    {
        $title = '后两位票数一样，重新投票：'.$vote['title'];
    }
    else
    {
        $title = $vote['title'];
    }
    echo '<tr class="'.$class.'">
                <td>
                    '.$title.'
                </td>
                <td>
                    '.$trans_status[$vote->status].'
                </td>
                <td>
                    '.date('Y-m-d',strtotime($vote->endTime)).'
                </td>
                <td>';
                switch($vote->status)
                    {
                        case 1:
                            echo Html::a('进入投票', ['vote/view','id'=>$vote['id']], ['class' => 'btn btn-success btn-sm']).'&nbsp';
                            if($banji['administrator']==Yii::$app->user->identity->username)
                            {
                                echo Html::a('结束投票', ['vote/endvote','id'=>$vote['id']], ['id'=>'but_vote_end','class' => 'btn btn-success btn-sm']).'&nbsp';
                                echo Html::a('取消投票', ['vote/delete','id'=>$vote['id']], ['id'=>'but_vote_cancel','class' => 'btn btn-danger btn-sm']);
                            }
                            break;
                        case 4:
                            echo Html::a('查看结果', ['vote/endvote','id'=>$vote['id']], ['class' => 'btn btn-success btn-sm']).'&nbsp';
                            if($banji['administrator']==Yii::$app->user->identity->username)
                            {
                                echo Html::a('重新投票', ['vote/beginvote','id'=>$vote['id']], ['id'=>'but_vote_revote','class' => 'btn btn-success btn-sm']).'&nbsp';
                                echo Html::a('取消投票', ['vote/delete','id'=>$vote['id']], ['id'=>'but_vote_cancel','class' => 'btn btn-danger btn-sm']);
                            }
                        default:
                            break;
                    }
                echo '</td><th>
            </th>
        </tr>';
}
function printvotetableend()
{
 echo '</tbody></table>';
}

function printhelptablehead($column1,$column2,$column3,$column4)
{
    echo '<table class="table">
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

function printhelptablebody($donatemodel,$class)
{
    $trans_status = [0=>'等待资助',1=>'等待商议资助计划',2=>'投票经行中',3=>'资助进行中',4=>'资助完成',5=>'逾期'];
    echo '<tr class="'.$class.'">
                    <td>
                        '.User::findOne(['id'=>$donatemodel['toWho']])->username.'
                    </td>
                    <td>
                        '.$donatemodel['totalMoney'].'元
                    </td>
                    <td>
                        '.$trans_status[$donatemodel['status']].'
                    </td>
                    <td>
                        '.$donatemodel['sentCount'].'/'.$donatemodel['count'].'
                    </td>
                 </tr>';
}
function printhelptableend()
{
 echo '</tbody></table>';
}

$js = <<<JS
    $('a[id="but_vote_delete"]').click(function()
    {
       return confirm("一旦删除不可恢复，确定删除该投票获得吗？");
    });
    $('a[id="but_vote_end"]').click(function()
    {
       return confirm("结束投票将产生投票结果，确定结束投票吗？");
    });
    $('a[id="but_vote_revote"]').click(function()
    {
       return confirm("确定重新开始投票吗？");
    });
    $('a[id="but_vote_cancel"]').click(function()
    {
       return confirm("取消将视为删除该投票活动，是否继续？");
    });
JS;

$this->registerJs($js);
?>
