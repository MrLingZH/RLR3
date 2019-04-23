<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;
use app\models\User;

$trans_name = array(
    'wish'=>'发布心愿',
    'editwish'=>'编辑心愿',
    );
$user = Yii::$app->user->identity;
$this->title = $trans_name[Yii::$app->controller->action->id];
$this->params['breadcrumbs'][] = ['label'=>'应用中心','url'=>\yii\helpers\Url::to(['site/appcenter'])];
if($this->title == '编辑心愿')
{
    if($user->degree == 'vip')$this->params['breadcrumbs'][] = ['label'=>'我的心愿','url'=>\yii\helpers\Url::to(['donate/mywish'])];
    if($user->degree == 'witness')$this->params['breadcrumbs'][] = ['label'=>'心愿申请','url'=>\yii\helpers\Url::to(['donate/list_apply'])];
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-6\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-2 control-label'],
    ],
]); ?>
  
<?php
    echo $form->field($model, 'count')->dropDownList(['1' => '1', '2' => '2','3' => '3', '4' => '4','5' => '5', '6' => '6','7' => '7', '8' => '8','9' => '9', '10' => '10','11' => '11','12'=>'一年','24'=>'两年','36'=>'三年','48'=>'四年','60'=>'五年','72'=>'六年']);
    echo $form->field($model, 'totalMoney');

    if($this->title  == '发布心愿' || ($this->title == '编辑心愿' && $user->degree == 'vip'))
    {
		echo $form->field($model, 'tag')->dropDownList(['1' => '贫困', '2' => '单亲','3' => '孤儿']);
        $listData=ArrayHelper::map($allschool,'id','name');
        echo $form->field($model, 'schoolid')->dropDownList($listData,['prompt'=>'选择社区']);
        
        echo $form->field($model, 'schoolnumber');
        echo $form->field($model, 'guardian_name');
        echo $form->field($model, 'guardian_tel');
        echo $form->field($model, 'guardian_cardnumber');
		echo $form->field($model, 'description')->textarea(['rows'=>10,'style'=>'resize:none']);
    }
    if($user->degree == 'witness'){
        echo DetailView::widget([
            'model' => $wish,
            'attributes' => [
                ['label'=>'申请人','value'=>User::findOne(['id'=>$wish['toWho']])->username],
                ['label'=>'申请时间','value'=>$wish['wishtime']],
                ['label'=>'电话','value'=>$user->tel],
                ['label'=>'邮箱','value'=>$user->email],
                ['label'=>'申请人详述','value'=>$wish['description']],
                ['label'=>'监护人','value'=>$wish['guardian_name']],
                ['label'=>'监护人电话','value'=>$wish['guardian_tel']],
                ['label'=>'监护人卡号','value'=>$wish['guardian_cardnumber']],
            ],
        ]);
    }

    //Javascript实现表单自动填充
    $schoolnumber = [];
    $schoolid = [];
    foreach($allschool as $value)
    {
        $schoolnumber[$value->id] = $value->schoolnumber;
        $schoolid[$value->schoolnumber] = $value->id;
    }
    $js1 = 'var schoolnumber = '.json_encode($schoolnumber).';
            var schoolid = '.json_encode($schoolid).';';
    $js2 = <<<EOF
    $('#wishform-schoolid').change(function(){
        $('#wishform-schoolnumber').val(schoolnumber[$(this).val()]);
        });
    $('#wishform-schoolnumber').change(function(){
        if(!schoolid[$(this).val()])
        {
            alert('无效的社区代码');
            $('#wishform-schoolid').val('');
            $('#wishform-schoolnumber').val('');
        }
        else
        {
            $('#wishform-schoolid').val(schoolid[$(this).val()]);
        }
        });
EOF;
    $this->registerJs($js1);
    $this->registerJs($js2);
?>

    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-11">
            <?= Html::submitButton('确认', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>
    </div>

<?php ActiveForm::end();?>