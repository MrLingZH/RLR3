<?php

namespace app\models;

use Yii;
use yii\base\Model;

class SimpleForm extends Model
{
	//该表单为各种简单的表单集合
	
	public $reason;//理由
	public $money;//金额
	public $toWho;//对方用户名
	public $toClass;//对方团体
	
	public function rules()
	{
		return [
			[['reason','money','toWho'],'required'],
			['money','integer','min'=>1,'max'=>100000000],
		];
	}

	public function attributeLabels()
	{
		return [
			'reason'=>'理由',
			'money'=>'金额',
			'toWho'=>'对方用户名',
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