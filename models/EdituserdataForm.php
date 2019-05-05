<?php

namespace app\models;

use Yii;
use yii\base\Model;

class EdituserdataForm extends Model
{
	public $id;
	public $username;
    public $nickname;
    public $email;
    public $tel;
    public $sex;
    public $audit_school;  //审计学校
    public $reg_school;  //注册学校
    public $school;  //所属学校
    public $register_time; //注册时间
	//public $avatar_show;  //库中暂时无此字段

	//modify begin

	 /**
     * @var UploadedFile
     */
    public $upside_of_idcard; //用于上传身份证文件，可文件系统尚未编写

    public $downside_of_idcard; //用于上传身份证文件，可文件系统尚未编写

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['nickname','tel','sex'], 'required'],
            ['tel','match','pattern'=>'/^1[34578]\d{9}$/','message'=>'手机号格式不正确！'],
            //[['upside_of_idcard','downside_of_idcard',], 'image', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'], //用于上传身份证文件，可文件系统尚未编写
            // ['username','validateUniqueUsername'],  //注册系统有实现此功能，这里注掉
        ];
    }

    public $trans_sex = array(
        ['cn'=>'男','eng'=>'man',],
        ['cn'=>'女','eng'=>'woman',],
        );

	//modify start
	public $trans_avatar_show = array(
        ['desc'=>'全部不可见','val'=>0,],
        ['desc'=>'注册用户可见','val'=>1,],
        ['desc'=>'注册用户、游客都可见','val'=>2,],
    );
	//modify end

	//(汉化)
    public function attributeLabels()
    {
        return [
        	'username'=>'用户名',
            'nickname'=>'真实姓名',
            'tel'=>'电话',
            'email'=>'邮箱',
            'sex'=>'性别',
            'avatar_show'=>'头像可见性',
            'upside_of_idcard'=>'身份证正面',
            'downside_of_idcard'=>'身份证反面',
            'reg_school'=>'注册学校',
            'audit_school'=>'审核学校',
            'school'=>'所属学校',
            'register_time'=>'注册时间',
        ];
    }

    /*
    public function validateUniqueUsername($attribute, $params)
    {
        $usermodel = User::findById($this->id);
        $usernameExist = User::isUsernameExist($this->username);
        if (!$this->hasErrors()) {
        	if ($usernameExist&&($usermodel!=$usernameExist)){
                 $this->addError($attribute,'用户id被占用，您换个别的试试。');
            }
        }
    } 
   */

    public function update()
    {
        if($this->validate()) {
            $usermodel = User::findIdentity($this->id);
            $usermodel->sex = $this->sex;
            $usermodel->nickname = $this->nickname;
            $usermodel->tel = $this->tel;
            /*用于上传身份证正反面图片
            $folder_path = './upload_user/idcard/'.$this->id;
            $upside_of_idcard_filepath = $folder_path.'/upside_of_idcard'.'.'.$this->upside_of_idcard->extension;
            $downside_of_idcard_filepath = $folder_path.'/downside_of_idcard'.'.'.$this->downside_of_idcard->extension;
            if (!file_exists($folder_path))
            {
                mkdir($folder_path, 0777, true);
            }
            $this->upside_of_idcard->saveAs($upside_of_idcard_filepath);
            $this->downside_of_idcard->saveAs($downside_of_idcard_filepath);

            $usermodel->upside_of_idcard = $upside_of_idcard_filepath;
            $usermodel->downside_of_idcard = $downside_of_idcard_filepath;
            暂时阉割此功能
			$usermodel->avatar_show = $this->avatar_show;//modify
			file_put_contents('/log.txt',var_export($this->avatar_show,true));
			*/
            $usermodel->save();
            
            return true;
        }
        else {
            return false;
        }
    }

}


?>