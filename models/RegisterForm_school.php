<?php

namespace app\models;

use Yii;
use yii\base\Model;

class RegisterForm_School extends Model
{
	public $username;
	public $password;
	public $repassword;
	public $email;
	public $tel;
	public $code;
	public $schoolname;
	public $acknowledgement = true;//是否同意使用协议
	
	public function rules()
	{
		return [
			['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 16],
            ['username','match','pattern'=>'/^[\x{4e00}-\x{9fa5}A-Za-z0-9]{1,16}$/u','message'=>'用户名不能含有特殊字符'],
            ['username','validateUsername'],
            
            ['code','validateCode'],
			
            [['password','repassword'], 'required'],
			[['password','repassword'], 'string', 'min' => 6],
			['repassword', 'compare', 'compareAttribute' => 'password','message'=>'两次输入的密码不一致！'],

			['code','required'],
			['schoolname','required'],
			['schoolname','match','pattern'=>'/^[\x{4e00}-\x{9fa5}A-Za-z0-9]{1,16}$/u','message'=>'学校名称不能含有特殊字符'],
			['tel','match','pattern'=>'/^1[34578]\d{9}$/','message'=>'手机号码格式错误'],

			['acknowledgement', 'boolean'],
			['acknowledgement','compare','compareValue' => true, 'operator' => '==','message'=>'必须同意使用协议方可注册'],

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
			'username'=>'用户名',
			'password'=>'密码',
			'repassword'=>'重复密码',
			'tel'=>'电话号码(可不填)',
			'schoolname'=>'学校名称',
		];
	}

	public function validateUsername($attribute, $params)
    {
        $usernameExist = User::findByUsername($this->username);
        if (!$this->hasErrors()) {
            if($usernameExist){
                $this->addError($attribute,'该用户名已被注册！');
            }
        }
    }

    public function validateEmail($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if($t_user = User::findOne(['email'=>$this->email])){
            	if($t_user->isVerfied == 1)
            	{
            		$this->addError($attribute,'该邮箱已被注册！');
            	}
            }
        }
    }

    public function validateCode($attribute, $params)
    {
        $user = User::findByEmail($this->email);
        if (!$this->hasErrors()) {
            if($user->verifyCode != $this->code){
                $this->addError($attribute,'验证码不正确！');
            }
        }
    }

    public function beforSubmit()
    {
    	if($this->validate())
    	{
    		return true;
    	}
    	return false;
    }
}


?>