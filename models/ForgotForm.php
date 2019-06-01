<?php

namespace app\models;

use Yii;
use yii\base\Model;

class ForgotForm extends Model
{
	public $password;
	public $repassword;
	public $email;
	public $code;
	
	public function rules()
	{
		return [
            [['password','repassword'], 'required'],
			[['password','repassword'], 'string', 'min' => 6],
			['repassword', 'compare', 'compareAttribute' => 'password','message'=>'两次输入的密码不一致！'],

			['code','required'],
			['code','validateCode'],

			['email','required'],
			['email','email'],
			['email','validateEmail'],
		];
	}

	public function attributeLabels()
	{
		return [
			'email'=>'电子邮箱',
			'code'=>'验证码',
			'password'=>'新密码',
			'repassword'=>'重复密码',
		];
	}

    public function validateEmail($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if($t_user = User::findOne(['email'=>$this->email])){
            	if(!$t_user)
            	{
            		$this->addError($attribute,'该邮箱账号不存在。');
            	}
            }
        }
    }

    public function validateCode($attribute, $params)
    {
        $user = User::findByEmail($this->email);
        if (!$this->hasErrors()) {
            if($this->code == null || $user->verifyCode != $this->code){
                $this->addError($attribute,'验证码不正确！');
            }
        }
    }
}
?>