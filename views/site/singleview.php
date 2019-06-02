<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;

$trans_name = [
	'getcurrentuserdata' => '个人资料',
	'edituserdata' => '编辑资料',
    'singleview' => '查看资料',
];
//汉化
$translate = [
    'vip' => '会员',
    'admin' => '管理员',
    'witness' => '见证人',
];

$this->title = $trans_name[Yii::$app->controller->action->id];
$this->params['breadcrumbs'][] = ['label'=>'应用中心','url'=>\yii\helpers\Url::to(['site/appcenter'])];
$this->params['breadcrumbs'][] = $this->title;

//die('111');
?>

<?php
	if($this->title == '个人资料'):
?>

<h3><?=$SingleView['username'] ?>的个人资料</br></br></h3>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
        	<?php //echo Html::img($user['headimage'],['class' =>'img-responsive','width'=>'150px','alt'=>'用户未上传头像']);?>
            <img class="img-responsive" src=<?=$model->headimage?> width="150px" alt="用户未上传头像"><br>
         <?= Html::a('上传头像', Url::to(['site/uploadheadimage']),['class' => 'btn btn-info btn-sm', 'name' => 'view-button']) ?>&nbsp
         <?=  Html::a('完善个人资料', ['site/edituserdata'], ['class' => 'btn btn-success btn-sm']); ?>&nbsp
         <?=  Html::a('修改密码', ['site/reset_psw'], ['class' => 'btn btn-primary btn-sm']); ?>
        </div>
    </div>
</div>

<br><br>

<?=DetailView::widget([
    'model' => $SingleView,
    'attributes' => [
        ['label'=>'用户id','value'=>$SingleView['username']],
        ['label'=>'真实姓名','value'=>$SingleView['nickname']],
        ['label'=>'电话','value'=>$SingleView['tel']],
        [
           'label'=>'身份证正面',
            'value'=> $SingleView['idcard_downside'],//.$model->upside_of_idcard,//这是身份证图片路径
            'format' => ['image',['width'=>'400','alt'=>"用户未上传"]],
        ],
        [
            'label'=>'身份证反面',
            'value'=> $SingleView['idcard_upside'],//.$model->downside_of_idcard,//这是身份证图片路径
            'format' => ['image',['width'=>'400','alt'=>"用户未上传"]],
        ],
        ['label'=>'性别','value'=>$SingleView['sex']],
        //['label'=>'审核学校','value'=>$SingleView['audit_school']],
        //['label'=>'注册学校','value'=>$SingleView['reg_school']],
        ['label'=>'所属学校','value'=>$SingleView['school']],
        ['label'=>'帐号类别','value'=>$translate[$SingleView['degree']]],
        ['label'=>'电子邮件','value'=>$SingleView['email']],
        ['label'=>'账户余额','value'=>$SingleView['money']],//这是该用户余额，字段$user->money
        ['label'=>'帐号验证状态','value'=>$SingleView['isVeified']],
        ['label'=>'注册时间','value'=>$SingleView['register_time']],
    ],
])?>

<?php
	elseif($this->title == '查看资料'):
?>
<?=DetailView::widget([
    'model' => $SingleView,
    'attributes' => [
        ['label'=>'用户id','value'=>$SingleView['username']],
        ['label'=>'真实姓名','value'=>$SingleView['nickname']],
        ['label'=>'电话','value'=>$SingleView['tel']],
        [
            'label'=>'身份证正面',
            'value'=> $SingleView['idcard_downside'],//.$model->upside_of_idcard,//这是身份证图片路径
            'format' => ['image',['width'=>'400','alt'=>"用户未上传"]],
        ],
        [
            'label'=>'身份证反面',
            'value'=> $SingleView['idcard_upside'],//.$model->downside_of_idcard,//这是身份证图片路径
            'format' => ['image',['width'=>'400','alt'=>"用户未上传"]],
        ],
        ['label'=>'性别','value'=>$SingleView['sex']],
        ['label'=>'审核学校','value'=>$SingleView['audit_school']],
        ['label'=>'注册学校','value'=>$SingleView['reg_school']],
        ['label'=>'所属学校','value'=>$SingleView['school']],
        ['label'=>'帐号类别','value'=>$translate[$SingleView['degree']]],
        ['label'=>'电子邮件','value'=>$SingleView['email']],
        ['label'=>'账户余额','value'=>$SingleView['money']],
        ['label'=>'帐号验证状态','value'=>$SingleView['isVeified']],
        ['label'=>'注册时间','value'=>$SingleView['register_time']],
    ],
])?>


<?php
	elseif($this->title == '编辑资料'):
?>

<?php $form = ActiveForm::begin([ //form开始
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal','enctype' => 'multipart/form-data'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-6\">{input}</div>\n<div class=\"col-lg-2\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); 
	$listdata = ArrayHelper::map($editform->trans_sex,'eng','cn');
	$listdataTwo = ArrayHelper::map($editform->trans_avatar_show,'val','desc');
?>
    <?=$form->Field($editform,'username')->textInput(['readonly' => 'true'])?>
    <?=$form->field($editform,'nickname')?>
    <?=$form->field($editform,'tel')?>
    <?=$form->field($editform,'email')->textInput(['readonly' => 'true'])?>
    <?=$form->field($editform,'sex')->dropDownList($listdata, ['prompt'=>'选择性别'])?>

    <?php if(0 === 1):?>    
	<?=$form->field($editform,'avatar_show')->dropDownList($listdataTwo, ['prompt'=>'选择头像可见性'])?><!--目前阉割此功能-->
	<?php endif; ?>

    <?php if(1):?>
    <?= $form->field($editform, 'upside_of_idcard')->fileInput() ?>
    <?= $form->field($editform, 'downside_of_idcard')->fileInput() ?>
	<?php endif; ?>

	<div class="form-group">
        <div class="col-lg-offset-2 col-lg-11">
            <?= Html::submitButton('确认', ['class' => 'btn btn-success', 'name' => 'login-button']) ?>
        </div>
    </div>
<?php ActiveForm::end()//form结束; ?> 

<?php
	endif;
?>