<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "wish".
 *
 * @property int $id
 * @property int $toWho 受资助的人
 * @property int $fromWho 提供资助的人
 * @property int $fromClass 提供资助的团体
 * @property int $auditor 审核人
 * @property int $school 申请的社区
 * @property string $protocol 上传协议
 * @property int $vote 负责投票的团体
 * @property int $count 总的握手期数
 * @property string $description 心愿描述
 * @property int $donateinterval 捐款间隔，单位月(30天)
 * @property string $donatetime 握手达成时间
 * @property int $totalMoney 总共需要捐款的金额
 * @property int $sentCount 已握手的期数
 * @property string $applytime 握手申请时间
 * @property string $timeout 握手超时时间
 * @property string $installment 分期方式
 * @property int $result 审核结果,0=待处理，1=同意，2=拒绝，3=删除
 * @property string $reason 同意过审原因
 * @property int $status 状态，0=等待资助，1=等待商议资助计划，2=投票进行中，3=资助进行中，4=资助完成，5=逾期。
 * @property string $guardian_name 监护人姓名
 * @property string $guardian_tel 监护人电话
 * @property string $guardian_cardnumber 监护人卡号
 * @property string $tag 类型
 */
class Wish extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wish';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['toWho', 'fromWho', 'fromClass', 'auditor', 'school', 'vote', 'count', 'donateinterval', 'totalMoney', 'sentCount', 'result', 'status'], 'integer'],
            [['donatetime', 'applytime', 'timeout'], 'safe'],
            [['protocol', 'description', 'installment', 'reason', 'guardian_name', 'guardian_tel', 'guardian_cardnumber', 'tag'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'toWho' => 'To Who',
            'fromWho' => 'From Who',
            'fromClass' => 'From Class',
            'auditor' => 'Auditor',
            'school' => 'School',
            'protocol' => 'Protocol',
            'vote' => 'Vote',
            'count' => 'Count',
            'description' => 'Description',
            'donateinterval' => 'Donateinterval',
            'donatetime' => 'Donatetime',
            'totalMoney' => 'Total Money',
            'sentCount' => 'Sent Count',
            'applytime' => 'Applytime',
            'timeout' => 'Timeout',
            'installment' => 'Installment',
            'result' => 'Result',
            'reason' => 'Reason',
            'status' => 'Status',
            'guardian_name' => 'Guardian Name',
            'guardian_tel' => 'Guardian Tel',
            'guardian_cardnumber' => 'Guardian Cardnumber',
            'tag' => 'Tag',
        ];
    }

    public static function getMyWishCount($id)
    {
        return count(static::findAll(['toWho'=>$id]));
    }

    public function getProgressView()
    {
    if($this->status != 3 && $this->status != 4){return '';}

    $allreadyPercent = $this->sentCount/$this->count*100;
    $needtoPercent = $this->donateinterval/$this->count*100;

    $first = <<<EOF
    <div class="progress-bar progress-bar-success active" role="progressbar" style="width:$allreadyPercent%">
    已经划账期数:$this->sentCount
    </div>
EOF;

    $rest = "";
    for($i=$this->sentCount*($this->donateinterval);$i<$this->count;$i=$i+$this->donateinterval)
    {
        $date=date("Y-m-d",$this->donatetime + strtotime('+'.($i * $this->donateinterval).' month'));
        if($i==$this->sentCount)
        {
            $class="progress-bar-danger active";
        }
        else
        {
            $class = "progress-bar-warning ";
        }
        $rest.="<div class=\"progress-bar progress-bar-striped $class\" role=\"progressbar\" style=\"width:$needtoPercent%\">$date</div>";
    }

    $rlt = <<<EOF
    <div id="progressbar_$this->id">
    $first
    $rest
    </div>
EOF;

    return $rlt;
    }
}
