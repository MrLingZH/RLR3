<?php

namespace app\models;

use yii\base\Model;

class WishForm extends Model
{
	public $count;//握手期数
	public $totalMoney;
	public $tag;
	public $schoolid;
	public $schoolnumber;
	public $guardian_tel;
	public $guardian_name;
	public $guardian_cardnumber;
	public $description;

	public function rules()
	{
		return [
			[['totalMoney','description','schoolnumber'],'required'],

			['schoolid','validateSchoolid'],
			['guardian_tel','match','pattern'=>'/^1[34578]\d{9}$/','message'=>'手机号码格式错误'],
		];
	}

	public function attributeLabels()
	{
		return [
			'count'=>'总时间(月)',
			'totalMoney'=>'总金额',
			'tag'=>'选择标签',
			'schoolid'=>'选择社区',
			'schoolnumber'=>'社区代码',
			'guardian_name'=>'监护人姓名（选填）',
			'guardian_tel'=>'监护人电话（选填）',
			'guardian_cardnumber'=>'监护人卡号（选填）',
			'description'=>'申请理由',
		];
	}

	public function validateSchoolid($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if($this->schoolid == '0'){
                $this->addError($attribute,'请选择社区！');
            }
        }
    }

	public function wish()
    {
    	if($this->validate())
    	{
    		return true;
    	}
    	return false;
    }
}
?>