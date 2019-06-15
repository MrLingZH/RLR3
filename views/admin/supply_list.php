<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\User;

$this->title = $title0;
$this->params['breadcrumbs'][] = ['label'=>'应用中心','url'=>\yii\helpers\Url::to(['site/appcenter'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<h2><?=$this->title?></h2>
<?php
function printtalehead($column1,$column2,$column3,$column4,$column5)
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
                        <th>
                           '.$column5.'
                        </th>
                        </tr>
                </thead>
                 <tbody>';
}
function printtablebody($column1,$column2,$column3,$column4,$column5,$column6,$class,$type,$title)
{
    $trans_result = [
        0 => '待审核',
        1 => '已通过',
        2 => '已拒绝',
    ];
      echo '<tr class="'.$class.'">
                        <td>
                            '.$column1.'
                        </td>
                        <td>
                            '.$column2.'
                        </td>
                        <td>
                            '.$column3.'
                        </td>';
                        if($title == '待审核' || $title == '审核通过')
                        {
                            echo '<td>'.Html::a($column4, Url::to(['school/view','id'=>$column6]),['class' => 'btn btn-info btn-xs']).'</td>';
                        }
                        else
                        {
                            echo '<td>'.$column4.'</td>';
                        }
                        echo '<td>'.$trans_result[$column5].'</td>';
                        echo '<td>'.Html::a('承诺书', Url::to(['school/viewcertificate','id'=>$column6]),['class' => 'btn btn-success btn-xs']).'</td>';
                        if($title == '待审核')
                        {
                        echo '<td>
                            '.Html::a('修改申请', Url::to(['school/update','id'=>$column6]),['class' => 'btn btn-success btn-xs']).'
                            </td>
                            <td>
                                '.Html::a('同意', Url::to(['admin/agreed_apply_school','id'=>$column6]),['class' => 'btn btn-success btn-xs']).'
                                '.Html::a('拒绝', Url::to(['admin/disagreed_apply_school','id'=>$column6]),['class' => 'btn btn-success btn-xs']).'
                            </td>
                            </tr>';
                        }
                        
}
function printtableend()
{
    echo '</tbody></table>';
}
$models = $provider->getModels();
$pages = $provider->getPagination();
    printtalehead('申请者','事务','日期','相关社区','状态');
    foreach ($models as $type => $model) 
    {
       printtablebody(
        User::findOne(['id'=>$model->witnessid])->username,
        '学校注册申请',
        $model->registertime,
        $model->registername,
        $model->registerresult,
        $model->id,
        '',
        $type,
        $title0
        );
    }
printtableend();
echo yii\widgets\LinkPager::widget([
    'pagination' => $pages,
]);
?>
