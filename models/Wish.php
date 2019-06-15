<?php

namespace app\models;

use Yii;
use app\models\Trade;
use app\models\Message;
use app\models\User;
use app\models\Banji;

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
    for($i=1;$i<$this->count;$i++)
    {
        $date=date("Y-m-d",strtotime($this->donatetime.'+'.($i * $this->donateinterval).' month'));//划款日期，到了该日期系统自动转账。
        if($i > $this->sentCount)//未划款的期
        {
            if($date <= date('Y-m-d'))//已到达划款日的期
            {
                $class="progress-bar-danger active";
            }
            else//还未到划款日的期
            {
                $class = "progress-bar-warning ";
            }
            $rest.="<div class=\"progress-bar progress-bar-striped $class\" role=\"progressbar\" style=\"width:$needtoPercent%\">$date</div>";
        }
    }

    $rlt = <<<EOF
    <div id="progressbar_$this->id">
    $first
    $rest
    </div>
EOF;

    return $rlt;
    }

    //判断用户能否资助该心愿，因为只能资助在本社区的心愿
    public function canDonate($schoolWithUser)
    {
        if($schoolWithUser == $this->school)return true;
        return false;
    }

    //获取需要划款的期数
    public function getNeedTransferCount()
    {
        $needTransferCount = 0;
        for($i=1;$i<$this->count;$i++)
        {
            $date=date("Y-m-d",strtotime($this->donatetime.'+'.($i * $this->donateinterval).' month'));//划款日期，到了该日期系统自动转账。
            if($i > $this->sentCount)//未划款的期
            {
                if($date <= date('Y-m-d'))//已到达划款日的期
                {
                    $needTransferCount += 1;
                }
                else
                {
                    break;
                }
            }
        }
        return $needTransferCount;
    }

    //定时执行，查询该划款的心愿，自动划款。
    public static function transferToWish()
    {
        $result = [0=>0,1=>0];
        $wish = self::findAll(['status'=>3]);
        foreach($wish as $v)
        {
            $needTransferCount = $v->getNeedTransferCount();
            if($needTransferCount > 0)
            {
                for($i=1;$i<=$needTransferCount;$i++)//循环处理每一期
                {
                    $trade = new Trade;
                    if(isset($v->fromWho))//判断是个人资助还是团体资助
                    {
                        if($trade->transfertoperson($v->fromWho,$v->toWho,$v->totalMoney/$v->count))
                        {
                            $v->sendMessage_transferSucceed();//发送消息提醒
                            $v->sentCount += 1;
                            $v->save();
                            $result[1] += 1;
                        }
                        else//余额不足
                        {
                            $v->sendMessage_transferFailed();//发送消息催促充钱
                            $result[0] += 1;
                        }
                    }
                    else
                    {
                        if($trade->transfer_ClassToPerson($v->fromClass,$v->toWho,$v->totalMoney/$v->count))
                        {
                            $v->sendMessage_transferSucceed();
                            $v->sentCount += 1;
                            $v->save();
                            $result[1] += 1;
                        }
                        else//余额不足
                        {
                            $v->sendMessage_transferFailed();
                            $result[0] += 1;
                        }
                    }
                }
            }
        }
        return $result;
    }

    public function sendMessage_transferSucceed()
    {
        $toWho = User::findOne(['id'=>$this->toWho]);
        if(isset($this->fromWho))
        { 
            $fromWho = User::findOne(['id'=>$this->fromWho]);
            $body = $toWho->nickname.'你好，你已收到来自个人<font style="color:green">'.$fromWho->username.'</font>的资助款'.$this->totalMoney/$this->count.'元。';
        }
        else
        {
            $fromClass = Banji::findOne(['id'=>$this->fromClass]);
            $fromWho = User::findOne(['id'=>$fromClass->administrator]);
            $body = $toWho->nickname.'你好，你已收到来自团体<font style="color:green">'.$fromClass->name.'</font>的资助款'.$this->totalMoney/$this->count.'元。';
        }
        $mail= Yii::$app->mailer->compose();
        $mail->setTo($toWho->email);  
        $mail->setSubject("人恋人平台资助款到账通知");
        $mail->setHtmlBody($body);
        $mail->send();

        //发给捐款方的
        $body = $fromWho->nickname.'你好，系统已自动完成心愿资助划款，收款方为<font style="color:green">'.$toWho->nickname.'</font>。划款：'.$this->totalMoney/$this->count.'元。';
        $mail= Yii::$app->mailer->compose();
        $mail->setTo($fromWho->email);  
        $mail->setSubject("人恋人平台自动划款通知");
        $mail->setHtmlBody($body);
        $mail->send();
    }
    public function sendMessage_transferFailed()
    {
        $toWho = User::findOne(['id'=>$this->toWho]);
        if(isset($this->fromWho))
        { 
            $fromWho = User::findOne(['id'=>$this->fromWho]);
            $body = $fromWho->nickname.'你好，你的余额不足以为<font style="color:green">'.$toWho->nickname.'</font>划款：'.$this->totalMoney/$this->count.'元，请及时充值以继续资助计划。';
        }
        else
        {
            $fromClass = Banji::findOne(['id'=>$this->fromClass]);
            $fromWho = User::findOne(['id'=>$fromClass->administrator]);
            $body = $fromWho->nickname.'你好，你的团体余额不足以为<font style="color:green">'.$toWho->nickname.'</font>划款：'.$this->totalMoney/$this->count.'元，请为团体充值以继续资助计划。';
        }
        $mail= Yii::$app->mailer->compose();
        $mail->setTo($fromWho->email);  
        $mail->setSubject("人恋人平台资助资金不足通知");
        $mail->setHtmlBody($body);
        $mail->send();
    }
}
