<?php

namespace app\models;

use Yii;
use yii\base\Model;

class RegisterForm extends Model
{
	public $email;
	public $code;
	
	public function rules()
	{
		return [
			['email','required'],
			['email','email'],
		];
	}

	public function attributeLabels()
	{
		return [
			'email'=>'电子邮箱',
		];
	}

	public function getVerifyCode()
	{
		$this->code = (string)mt_rand(10000,99999);
		return $this->code;
	}

	public function sendEmail()
	{
		$to = $this->email;
        $subject = "《人恋人公益平台》注册码";
        $body = "亲爱的".$this->email."您好，这是您的注册验证码：".$this->code."。感谢您的注册！";

		$mail = Yii::$app->mailer->compose(); //加载配置的组件
        $mail->setTo($to); //要发给谁
        $mail->setSubject($subject); //标题 主题
        $mail->setHtmlBody($body); //要发送的内容
        
        return $mail->send();
	}
}


?>