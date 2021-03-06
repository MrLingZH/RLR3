<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$trans_title = [
    'mybanji'=>'我创建的团体',
    'banjiincludeme'=>'我加入的团体',
];

$this->title = $trans_title[Yii::$app->controller->action->id];
$this->params['breadcrumbs'][] = ['label'=>'应用中心','url'=>\yii\helpers\Url::to(['site/appcenter'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
$models = $provider->getModels();
$pages = $provider->getPagination();
function printtalehead($column1,$column2,$column3)
{
    echo '<table class="table table-hover">
            <thead>
                <tr>
                    <th>'.$column1.'</th>
                    <th>'.$column2.'</th>
                    <th>'.$column3.'</th>
                </tr>
            </thead>
            <tbody>';
}
function printtablebody($column1,$column2,$column3,$column4,$class,$title)
{
    echo '<tr class="'.$class.'">
        <td>'.$column1.'</td>
        <td>'.$column2.'</td>
        <td>'.$column3.'</td>
        <td>'.Html::a('进入团体', Url::to(['banji/banjimates','id'=>$column4])).'</td>
        <td>'.Html::a('详情', Url::to(['banji/mybanjidetail','id'=>$column4]));
    if($title == '我创建的团体'){echo '&nbsp'.Html::a('修改', Url::to(['banji/update','id'=>$column4]));}
    echo '</td>';
    echo '</tr>';
}
function printtableend()
{
    echo '</tbody></table>';
}
    printtalehead('团体名','所属社区','管理员');
    foreach ($models as $model) 
    {
        printtablebody($model['name'],$model['school'],$model['administrator'],$model['id'],'',$this->title);
    }
    printtableend();
    if ($models == null)
    {
       echo "
        <center>
            <h1>空空如也</h1>
        </center>
       ";
    }

//分页组件,数据数量大于$pages时会分页，并显示分页组件
echo yii\widgets\LinkPager::widget([
    'pagination' => $pages,
]);
?>