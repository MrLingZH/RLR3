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

	//邮件服务尚未搭建，暂设验证码
	public function getVerifyCode()
	{
		return '5275';
	}
}


?>