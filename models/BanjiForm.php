<?php

namespace app\models;

use yii\base\Model;

class BanjiForm extends Model
{
	public $name;

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
            'name' => '班级名称',
        ];
    }

	public function validateName($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if(Banji::findByName($this->name)){
                $this->addError($attribute,'班级名称已存在！');
            }
        }
    }

	public function create()
    {
    	if($this->validate())
    	{
    		return true;
    	}
    	return false;
    }

    public function getToken()
    {
    	$code = '0123456789abcdefghijklmnopqrstuvwxyz';
    	while(1)
    	{
    		$token = '';
    		for($i=1;$i<=8;$i++)
    		{
    			$token .= $code{mt_rand(0,35)};
    		}
    		if(!Banji::findByToken($token))break;
    	}
		return $token;
    }
}
?>