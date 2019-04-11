<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\School;

class RegisterForm_school extends Model
{
	public $name;
	public $subDomain;//二级域名
	
	public function rules()
	{
		return [
			['name','required'],
			['name','validateName'],
		];
	}

	public function attributeLabels()
	{
		return [
			'name'=>'学校名称',
		];
	}

	public function validateName($attribute, $params)
    {
        $nameExist = School::findByName($this->name);
        if (!$this->hasErrors()) {
            if($nameExist){
                $this->addError($attribute,'该学校名已被注册！');
            }
        }
    }

	//刚注册时随机生成二级域名
	public function getsubDomain()
	{
		$this->subDomain = 's';
		$code = '0123456789';
		while(1)
    	{
    		$this->subDomain .= $code{mt_rand(1,9)};
			for($i=1;$i<=4;$i++)
			{
				$this->subDomain .= $code{mt_rand(0,9)};
			}
    		if(!School::findBySubDomain($this->subDomain))break;
    	}
		return $this->subDomain;
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