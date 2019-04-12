<?php

use yii\helpers\Html;
use yii\captcha\Captcha;
use yii\helpers\Url;
use app\models\User;
use app\Controller\UserController;

$this->title = '应用中心';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h3>
                <i class="glyphicon glyphicon-user"></i><?= "管理员&nbsp".$user['username']?>
            </h3>
            <h3>
                提现金额：<?=$user['money']?>元
            </h3>

        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                    <a href=<?=Url::to(['message/messagetome'])?> class="list-group-item"> <span class="badge"><?= $count['message'] ?></span>我的站内信</a>
                    <a href=<?=Url::to(['message/fillinmessage'])?> class="list-group-item"> 发送站内信</a>
                    <a href=<?=Url::to(['event/indextome'])?> class="list-group-item">我的审批</a>
                    <a href=<?=Url::to(['news/fillinnewsbyadmin'])?> class="list-group-item"> 首页信息发布</a> 
                    <a href=<?=Url::to(['event/eventlist','result'=>'unjudge'])?> class="list-group-item">
                        <span class="badge"><?= $count['status0'] ?></span>待审批</a>
                    <a href=<?=Url::to(['event/eventlist','result'=>'disagreed'])?> class="list-group-item">
                        <span class="badge"><?= $count['status2'] ?></span>审核未通过</a>  
                    <a href=<?=Url::to(['event/eventlist','result'=>'agreed'])?> class="list-group-item">
                        <span class="badge"><? $count['status1'] ?></span>审核通过</a>  
                    <a href=<?=Url::to(['site/getcurrentuserdata'])?> class="list-group-item">个人资料</a>
                    <a href=<?=Url::to(['template/list'])?> class="list-group-item">
                        模板管理
                        <?php
                        if(date('Y-m-d')<'2017-07-30'){
                            ?>
                            <div class="new-img" style="left: 70px; top: -10px;"><img src="/image/new.png" alt=""></div>
                            <?php
                        }
                        ?>
                    </a>
            </div>
        </div>
        <div class="col-md-9">
            <div class="row placeholders">
            <div class="col-xs-6 col-sm-3 placeholder">
              <img src=<?=Yii::$app->request->baseUrl.'/image/people-2.jpg'?> class="img-responsive" alt="Generic placeholder thumbnail">
              <h4><?= Html::a('提现名单', Url::to(['money/withdrawlist']),['class' => 'btn btn-info', 'name' => 'view-button']) ?>
                </h4>
            </div>
          </div>
<h3>管理员事务</h3>
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
function printtablebody($column1,$column2,$column3,$column4,$column5,$column6,$class,$type)
{
      echo '<tr class="'.$class.'">
                        <td>
                            './*Html::a($column2, Url::to(['user/view','id'=>User::findOneBy(['username'=>$column2])->id]),['class' => 'btn btn-success btn-xs'])*/$column2.'
                        </td>
                        <td>
                            '.$column3.'
                        </td>
                        <td>
                           '.$column5.'
                        </td>
                        <td>
                           '.printtablebody_column4($column4,$column6,$type)
                           
                           .'
                        </td>
                        <td>
                           '.Html::a('修改申请', Url::to(['school/update','id'=>$column6]),['class' => 'btn btn-success btn-xs']).'
                        </td>
                        <td>
                           '.Html::a('承诺书', Url::to(['site/viewcertificate','id'=>$column1]),['class' => 'btn btn-success btn-xs']).'
                        </td>
                        <td>
                            '.Html::a('同意', Url::to(['event/agree','id'=>$column1]),['class' => 'btn btn-success btn-xs']).'
                            '.Html::a('拒绝', Url::to(['event/disagree','id'=>$column1]),['class' => 'btn btn-success btn-xs']).'
                        </td>
                    </tr>';
}
function printtableend()
{
    echo '</tbody></table>';
}
function printtablebody_column4($column4,$column6,$type)
{
    if($type == 'apply_school')
    {
       return $column4;
    }
    return Html::a($column4, Url::to(['school/view','id'=>$column6]),['class' => 'btn btn-info btn-xs']);
}
$models = $provider->getModels();
$pages = $provider->getPagination();
    printtalehead('申请者','事务','日期','相关社区');
    foreach ($models as $type => $model) 
    {
        if($type = 'apply_school')
        {
            foreach ($model as $key => $value)
            {
               printtablebody($value->id,
                $value->witnessid,
                '学校注册申请',
                $value->registername,
                $value->registertime,
                '',
                '',
                $type
                );
            }
        }
    }
printtableend();
echo yii\widgets\LinkPager::widget([
    'pagination' => $pages,
]);
//var_dump($models);
?>
        </div>
    </div>
</div>

