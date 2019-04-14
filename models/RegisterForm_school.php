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