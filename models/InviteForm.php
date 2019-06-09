<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Message;
use app\models\User;
use app\models\Invite;
use yii\helpers\Url;


class InviteForm extends Model
{
    public $email;
    public $banji;

    const THIRTY_DAYS_IN_SECONDS = 2592000;
   
    public function rules()
    {
        return [
            [['email','banji'],'required'],
            ['email','email'],
            ['email', 'validateEmail'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'email'=>'对方email',
        ];
    }

    public function validateEmail($attribute, $params)
    {
        if (!$this->hasErrors())
        {
            if(!$user = User::findOne(['email'=>$this->email]))return true;
            if($this->banji->isMate($user->id))
            {
                $this->addError($attribute, '该用户已加入此班级');
            }
            else if($user->degree != 'vip')
            {
                $this->addError($attribute, '无法邀请此类型用户');
            }
            else if($invite = Invite::findOne(['toWho'=>$user->id,'result'=>0]))
            {
                $this->addError($attribute, '已对该用户发出邀请！请耐心等待该用户进行处理。');
            }
        }
    }

    public function send()
    {
        $user = Yii::$app->user->identity;
        $mail= Yii::$app->mailer->compose();
        $mail->setTo($this->email);  
        $mail->setSubject("这是来自".$user->username."的人恋人社区成员邀请");
        $toWho = User::findOne(['email'=>$this->email]);
        if($toWho && $toWho->isVerfied)
        {
            $url = Yii::$app->getUrlManager()->createAbsoluteUrl(['site/index']);
            $body = $user->username."邀请你加入团体\"".$this->banji->name."\"，请及时登录人恋人平台进行处理。<br/><a href=".$url.'>点击传送至人恋人平台</a>';
        }
        else
        {
            $toWho = new User;
            $toWho->email = $this->email;
            $toWho->save();
            $toWho = User::findOne(['email'=>$this->email]);

            $url = Yii::$app->getUrlManager()->createAbsoluteUrl(['site/register']);
            $body = $user->username."邀请你加入团体\"".$this->banji->name."\"，请点击下面的链接进行注册且登录人恋人平台进行处理。<br/><a href=".$url.'>点击传送至人恋人平台</a>';
        }
        $mail->setHtmlBody($body);  //发布可以带html标签的文本
        if(!$mail->send())return false;

        $invite = new Invite;
        $invite->fromWho = $user->id;
        $invite->fromClass = $this->banji->id;
        $invite->toWho = $toWho->id;
        $invite->type = 'banjiinvite';
        $invite->sendTime = date('Y-m-d H:i:s');
        if(!$invite->save())return false;

        $message = new Message;
        $message->fromWho = $user->id;
        $message->toWho = $toWho->id;
        $message->title = '团体邀请';
        $message->content = $user->username.'邀请你加入团体“'.$this->banji->name.'”。';
        $message->sendTime = date('Y-m-d H:i:s');
        $message->invite = $invite->id;
        if(!$message->save())return false;
        return true;
    }
}
