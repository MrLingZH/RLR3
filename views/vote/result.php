<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use app\models\User;
use app\models\wish;

$this->title = '投票结果';
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    $rank = 0;
    foreach($result as $k => $v)
    {
    	$rank += 1;
    	$toWho = Wish::findOne(['id'=>$k])->toWho;
    	$name = User::findOne(['id'=>$toWho])->username;
    	echo '第'.$rank.'名：'.$name.'('.$v.'票)<br/>';
    }
    echo '<br/>';
    switch($status)
    {
    	case 1:
    		echo '后两位票数一样，无法确定结果，请返回重新投票或取消本次投票。';
    		break;
    	case 2:
    		echo '前'.($rank - 1).'名将选定为资助对象，请等待与学校的见证人进行协商执行资助计划。';
    		break;
    	default:
    		break;
    }
    ?> 
    <br/>
    <a href=<?=Yii::$app->request->getReferrer()?>>返回</a>
</div>


