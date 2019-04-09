<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model app\models\School */
/* @var $form ActiveForm */
$this->title = '查看团体详情';
$this->params['breadcrumbs'][] = ['label'=>'应用中心','url'=>\yii\helpers\Url::to(['site/appcenter'])];
$this->params['breadcrumbs'][] = ['label'=>'我创建的团体','url'=>\yii\helpers\Url::to(['class/mybanji'])];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="class-view">
<h1><?= Html::encode($data['name']) ?></h1>
<?=DetailView::widget([
    'model' => $data,
    'attributes' => [
        'id',
        'name',
        'administrator',
        'school',
    ],
])?>

</div>