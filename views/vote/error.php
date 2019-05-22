<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = '错误';
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        在Web服务器处理您的请求时发生上述错误。
    </p>
    <p>
        如果您认为这是服务器错误，请联系我们。谢谢您。
    </p>
</div>
