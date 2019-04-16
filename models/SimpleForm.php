<?php

namespace app\models;

use Yii;
use yii\base\Model;

class SimpleForm extends Model
{
	//该表单为各种简单的表单集合
	
	public $reson;//理由
	
	public function rules()
	{
		return [
			[['reson'],'required'],
		];
	}

	public function attributeLabels()
	{
		return [
			'reson'=>'理由',
		];
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