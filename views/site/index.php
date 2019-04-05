<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;
$this->title = '人恋人公益平台';
?>
<style>
.carousel-caption{
	text-shadow:none;
}
.img-center{
	margin: 0 auto;
	display:block;
}
.p-t{
	padding-top:5px;
}
.m-b{
	padding-bottom:20px;
}
</style>
    <div class="container">
      <div class="row row-offcanvas row-offcanvas-right">
            <div class="carousel slide" id="carousel-961686">
                <ol class="carousel-indicators">
                    <li class="active" data-slide-to="0" data-target="#carousel-961686">
                    </li>
                    <li data-slide-to="1" data-target="#carousel-961686">
                    </li>
                    <li data-slide-to="2" data-target="#carousel-961686">
                    </li>
                </ol>
                <div class="carousel-inner">
                    <div class="item active">
                        <img alt="Carousel Bootstrap First" src=<?=Yii::$app->request->baseUrl.'/image/image-head-1.jpg'?> />
                        <div class="carousel-caption">
                            <h4>
                                 <h1>欢迎<?=Yii::$app->user->isGuest ?  "您" : (isset(Yii::$app->user->identity->nickname)? Yii::$app->user->identity->nickname : Yii::$app->user->identity->username); ?></h1>
                            </h4>
                            <p>人恋人公益平台，专注校园公益，服务全国各类学校便捷开展各自校园公益活动，搭建学校、毕业校友、在校校友三方沟通平台，方便毕业校友帮助在校校友。目前，已入驻湖南、湖北、四川、重庆、河南、内蒙、宁夏等省市区的大学（学院）、高中、初中、小学及其它办学机构。欢迎全国其它学校入驻。</p>
                        </div>
                    </div>
                    <div class="item">
                        <img alt="Carousel Bootstrap Second" src=<?=Yii::$app->request->baseUrl.'/image/image-head-2.jpg'?> />
                        <div class="carousel-caption">
                            <h4>
                                <h1>服务周到</h1>
                            </h4>
                            <p>
                                活动资金通过第三方支付平台账户封闭管理
                            </p>
                        </div>
                    </div>
                    <div class="item">
                        <img alt="Carousel Bootstrap Third" src=<?=Yii::$app->request->baseUrl.'/image/image-head-3.jpg'?> />
                        <div class="carousel-caption">
                            <h4>
                              <h1>点对点</h1>
                            </h4>
                            <p>
                               活动感受度高
                            </p>
                        </div>
                    </div>
                </div> <a class="left carousel-control" href="#carousel-961686" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a> <a class="right carousel-control" href="#carousel-961686" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
            </div>
            <!-- carousel -->
            <!-- 欢迎您 -->
<div class="site-index">

    <div class="jumbotron">
    <?= Html::a('应用中心', Url::to(['site/appcenter']),['class' => 'btn btn-primary', 'name' => 'view-button']) ?>
    </div>

</div>