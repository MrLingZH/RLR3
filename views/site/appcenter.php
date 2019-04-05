<?php

use yii\helpers\Html;
use yii\captcha\Captcha;
use yii\helpers\Url;

$this->title = '应用中心';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
//var_dump($user);
    $trans_degree = [
        'vip'=>'会员',
    ];

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">

            <h3>
                <i class="glyphicon glyphicon-user"></i><?=$trans_degree[$user['degree']]."&nbsp".$user['username']?>
            </h3>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                    <br><?php echo Html::img($user['headimage'],['class' =>'img-responsive','width'=>'150px','alt'=>'用户未上传头像']);?>
                    <br><?= Html::a('上传头像', Url::to(['site/uploadheadimage']),['class' => 'btn btn-info', 'name' => 'view-button']) ?>&nbsp
                    <?= Html::a('完善个人资料', ['site/edituserdata'], ['class' => 'btn btn-success btn-sm']); ?><br><br>
                    <a href=<?=Url::to(['message/messagetome'])?> class="list-group-item">
                        <span class="badge"><?=1;?></span>我的站内信</a>
                    <a href=<?=Url::to(['message/fillinmessage'])?> class="list-group-item">
                        <span class="badge"></span>发送站内信</a>
                    <a href=<?=Url::to(['donate/donatetome'])?> class="list-group-item">
                        <span class="badge"><?=0;?></span>我的心愿</a>
                    <a href=<?=Url::to(['donate/mydonation'])?> class="list-group-item">
                        <span class="badge"><?=0;?></span>我的资助</a>
                    <a href=<?=Url::to(['class/myclasses'])?> class="list-group-item">创建的团体</a>  
                    <a href=<?=Url::to(['class/classesincludeme'])?> class="list-group-item">
                        <span class="badge"><?=0;?></span>加入的团体</a>       
                    <a href=<?=Url::to(['site/getcurrentuserdata'])?> class="list-group-item">个人资料</a>
                    <a href=<?=Url::to(['template/list'])?> class="list-group-item">
                        模板管理
                        <?php
                            if(date('Y-m-d')<'2017-07-30')
                            {
                        ?>
                            <div class="new-img" style="left: 70px; top: -10px;"><img src="/image/new.png" alt=""></div>
                        <?php
                            }
                        ?>
                    </a>
            </div>
        </div>

        <div class="col-md-9">
            <h3>账户余额:&nbsp<?=$user['money']/100?>元 &nbsp&nbsp&nbsp
            <?= Html::a('充值', Url::to(['money/recharge']),['class' => 'btn btn-primary', 'name' => 'view-button']) ?>
            <?= Html::a('提现', Url::to(['money/withdraw']),['class' => 'btn btn-primary', 'name' => 'view-button']) ?>
            <?= Html::a('转账', Url::to(['money/transfertoperson']),['class' => 'btn btn-primary', 'name' => 'view-button']) ?>
            </h3>
            <div class="row placeholders">
            <div class="col-xs-6 col-sm-3 placeholder">
              <img src=<?=Yii::$app->request->baseUrl.'/image/dream.gif'?> class="img-responsive" alt="Generic placeholder thumbnail">
              <h4><?= Html::a('资助他人', Url::to(['donate/needdonatelist']),['class' => 'btn btn-primary', 'name' => 'view-button']) ?>
                </h4>
            </div>
             <div class="col-xs-6 col-sm-3 placeholder">
              <img src=<?=Yii::$app->request->baseUrl.'/image/donate.jpg'?> class="img-responsive" alt="Generic placeholder thumbnail">
             <h4><?= Html::a('助学申请', Url::to(['donate/wish']),['class' => 'btn btn-primary', 'name' => 'view-button']) ?>
                </h4>
            </div>
            <div class="col-xs-6 col-sm-3 placeholder">
              <img src=<?=Yii::$app->request->baseUrl.'/image/team.jpg'?> class="img-responsive" alt="Generic placeholder thumbnail">
             <h4><?= Html::a('创建团体', Url::to(['class/create']),['class' => 'btn btn-primary', 'name' => 'view-button']) ?>
                </h4>
            </div>
        </div>

        
    </div>
</div>
