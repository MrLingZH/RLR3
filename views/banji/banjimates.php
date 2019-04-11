<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = '我的团体';
$this->params['breadcrumbs'][] = ['label'=>'应用中心','url'=>\yii\helpers\Url::to(['site/appcenter'])];
$this->params['breadcrumbs'][] = ['label'=>'我创建的团体','url'=>\yii\helpers\Url::to(['banji/mybanji'])];
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
    
    echo '<h3>人数未够投票</h3>';
    printvotetablehead('投票主题','截止时间');
    /*foreach ($allNotCompleteVotes as $model) 
    {
        printVoteNotCompleteTableBody($model['title'],$model['endTime'],$model['id'],'');
    }
    printvotetableend();*/
}
//$models = $provider->getModels();
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

function printvotetablehead($title,$endTime)
{
echo '<table class="table">
                    <thead>
                    <tr>
                        <th>
                            '.$title.'
                        </th>
                        <th>
                            '.$endTime.'
                        </th>
                    </tr>
                </thead>
                 <tbody>';
}
function printVoteNotCompleteTableBody($title,$endTime,$voteid,$class)
{
 echo '<tr class="'.$class.'">
                        <td>
                            '.$title.'
                        </td>
                        <td>
                            '.$endTime->format('Y-m-d H:i:s').'
                        </td> <td>';
                            echo Html::a('继续选择候选人', ['vote/continue','id'=>$voteid], ['class' => 'btn btn-success btn-sm']).'&nbsp';
                            echo Html::a('删除', ['vote/delete','id'=>$voteid], ['class' => 'btn btn-danger btn-sm']);
                            echo '</td>
                    </th>
                </tr>';
}
function printvotetablebody($vote,$classmodel,$class)
{
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
                                '.$vote['endTime']->format('Y-m-d H:i:s').'
                            </td> <td>';

                                echo Html::a('进入投票', ['vote/view','id'=>$vote['id']], ['class' => 'btn btn-success btn-sm']).'&nbsp';
                                if($classmodel['administrator']==Yii::$app->user->identity->username)
                                {
                                    switch ($vote->isComplete()) {
                                        case true:
                                            echo Html::a('结束投票', ['vote/endvote','id'=>$vote['id']], ['class' => 'btn btn-success btn-sm']);
                                            break;
                                        case false:
                                            echo Html::a('结束投票', ['vote/endvote','id'=>$vote['id']], ['class' => 'btn btn-danger btn-sm']);
                                            break;
                                        default:
                                            # code...
                                            break;
                                    }
                                }
                                echo '</td><th>
                        </th>
                    </tr>';
}
function printvotetableend()
{
 echo '</tbody></table>';
}

function printhelptablehead($column1,$column2,$column3)
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
                         <th>
                            '.$column3.'
                        </th>
                    </tr>
                </thead>
                 <tbody>';
}

function printhelptablebody($donatemodel,$class)
{
      echo '<tr class="'.$class.'">
                        <td>
                            '.$donatemodel['toWho']['username'].'
                        </td>
                        <td>
                            '.$donatemodel['totalMoney'].'元
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

//print vote table
// $models = $voteprovider->getModels();
echo '<h3>正在投票</h3>';
printvotetablehead('投票主题','截止时间');
/*foreach ($allVotesComplete as $vote) 
    {
        printvotetablebody($vote,$classmodel,'');
    }*/
printvotetableend();

echo '<h3>正在帮助</h3>';
printhelptablehead('姓名','总金额','已捐期数');
/*foreach ($DonateByUs as $donatemodel) 
    {
        printhelptablebody($donatemodel,'');
    }*/
printvotetableend();

echo '<h3>逾期项目</h3>';
printhelptablehead('姓名','总金额','已捐期数');
/*foreach ($DonateByUsOverDue as $donatemodel) 
    {
        printhelptablebody($donatemodel,'');
    }*/
printvotetableend();
//print classmate table
$models = $provider->getModels();
echo '<h3>同学录</h3>';
printclassmatestablehead('用户名','邮箱','');
foreach ($models as $model) 
{
    printclassmatestablebody($model['username'],$model['email'],'');
}
printclassmatestableend();
?>
