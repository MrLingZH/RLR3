<?php

use yii\helpers\Html;
use yii\captcha\Captcha;
use yii\helpers\Url;

$this->title = '应用中心';
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h3>
                <i class="glyphicon glyphicon-user"></i><?="见证人&nbsp".$user['username']?>
            </h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                    <a href=<?=Url::to(['message/index'])?> class="list-group-item">
                        <span class="badge"><?=$count['message']?></span>我的站内信</a>
                    <a href=<?=Url::to(['message/write'])?> class="list-group-item">发送站内信</a>
                    <a href=<?=Url::to(['news/fillinnews'])?> class="list-group-item"> 社区信息发布</a>
                    <a href=<?=Url::to(['school/view','id'=>$user->audit_school])?> class="list-group-item">社区资料</a>
                    <a href=<?=Url::to(['donate/wish_supply_list','status'=>0,'result'=>0])?> class="list-group-item"> 
                        <span class="badge"><?=$count['result0']?></span>待审批</a>
                    <a href=<?=Url::to(['donate/wish_supply_list','status'=>0,'result'=>2])?> class="list-group-item">
                        <span class="badge"><?=$count['result2']?></span>审核未通过</a>  
                    <a href=<?=Url::to(['donate/wish_supply_list','status'=>'default','result'=>1])?> class="list-group-item">
                        <span class="badge"><?=$count['result1']?></span>审核通过</a>
                    <a href=<?=Url::to(['donate/wish_supply_list','status'=>1,'result'=>1])?> class="list-group-item">
                        <span class="badge"><?=$count['status1']?></span>待定资助周期</a>
                    <a href=<?=Url::to(['donate/wish_supply_list','status'=>3,'result'=>1])?> class="list-group-item">
                        <span class="badge"><?=$count['status3']?></span>资助进行中</a>   
                    <a href=<?=Url::to(['donate/wish_supply_list','status'=>4,'result'=>1])?> class="list-group-item">
                        <span class="badge"><?=$count['status4']?></span>资助完成</a>    
                    
                    <a href=<?=Url::to(['event/indexbyme'])?> class="list-group-item">我的见证人申请</a>
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
              <img src=<?=Yii::$app->request->baseUrl.'/image/word.png'?> class="img-responsive" alt="Generic placeholder thumbnail">
              <h4><?= Html::a('承诺书模版下载', Yii::$app->request->baseUrl.'/models/承诺书模版.docx',['class' => 'btn btn-info', 'name' => 'view-button']) ?>
                </h4>
            </div>
            <div class="col-xs-6 col-sm-3 placeholder">
              <img src=<?=Yii::$app->request->baseUrl.'/image/word1.png'?> class="img-responsive" alt="Generic placeholder thumbnail">
              <h4><?= Html::a('心愿协议模板下载', Yii::$app->request->baseUrl.'/models/人恋人心愿协议模板.doc',['class' => 'btn btn-info', 'name' => 'view-button']) ?>
                </h4>
            </div>
            <div class="col-xs-6 col-sm-3 placeholder">
              <img src=<?=Yii::$app->request->baseUrl.'/image/upload.png'?> class="img-responsive" alt="Generic placeholder thumbnail">
              <h4><?= Html::a('提交承诺书', Url::to(['site/uploadcertificate']),['class' => 'btn btn-info', 'name' => 'view-button']) ?>
                </h4>
            </div>
            <div class="col-xs-6 col-sm-3 placeholder">
              <img src=<?=Yii::$app->request->baseUrl.'/image/auditor.jpg'?> class="img-responsive" alt="Generic placeholder thumbnail">
              <h4><?= Html::a('邀请审核员', Url::to(['school/inviteauditor']),['class' => 'btn btn-info', 'name' => 'view-button']) ?>
                </h4>
            </div>
          </div>
          
<h3>见证人事务</h3>
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
      echo '<tr class="'.$class.'">
                        <td>
                            '.$column2.'
                        </td>
                        <td>
                            '.$column3.'
                        </td>
                        <td>
                           '.$column4.'
                        </td>
                        <td>
                           '.$column5.'
                        </td>
                        <td>
                            '.Html::a('同意', Url::to(['donate/agreedonate','id'=>$column1])).'
                            '.Html::a('拒绝', Url::to(['donate/disagreedonate','id'=>$column1])).'
                            '.Html::a('删除', Url::to(['donate/deletedonate','id'=>$column1])).'
                        </td>
                    </tr>';
}
function printtableend()
{
    echo '</tbody></table>';
}
$models = $provider->getModels();
$pages = $provider->getPagination();
printtalehead('申请者','事务','相关社区','日期');
foreach ($models as $model) 
{
    printtablebody($model['id'],$model['fromWho'],$model['type'],$model['school'],$model['wishtime'],'');
}
printtableend();
echo yii\widgets\LinkPager::widget([
    'pagination' => $pages,
]);
?>
        </div>
    </div>
</div>

