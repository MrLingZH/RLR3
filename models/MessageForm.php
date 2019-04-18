<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

class MessageForm extends Model
{	
	public $toWho;
	public $title;
	public $content;
	public $fromWho;
	
	public function rules()
	{
		return [
			[['toWho','title','content'],'required'],
			['toWho','validateTowho'],
		];
	}

	public function attributeLabels()
	{
		return [
			'toWho'=>'收件人用户名',
			'title'=>'标题',
			'content'=>'内容',
		];
	}

	public function validateTowho($attribute, $params)
	{
		if (!$this->hasErrors()) {
            if(!User::findOne(['username'=>$this->toWho])){
                $this->addError($attribute,'用户名不存在');
            }
            if($this->toWho == $this->fromWho){
                $this->addError($attribute,'不能发送给自己');
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