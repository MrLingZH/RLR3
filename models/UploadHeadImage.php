<?php


namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;
use app\models\User;
use yii\validators\FileValidator;

class UploadHeadImage extends Model
{
    /**
     * @var UploadedFile
     */
    public $userid;
    public $file;


    public function rules()
    {
        return [
            [['file'], 'image', 'skipOnEmpty' => false, 'extensions' => 'png, jpg',],
        ];
    }

    public function attributeLabels()
    {
        return [
            'file'=>'头像',
        ];
    }

    public function upload()
    {
        if($this->validate())
        {
            $usermodel = User::findOne(['id'=>$this->userid]);
            $folder_path = './upload_user/'.$usermodel->email.'/headimage';
            $head_image_file_path = $folder_path.'/headimage'.'.'.$this->file->extension;
            if (!file_exists($folder_path))
            {
                mkdir($folder_path, 0777, true);
            }
            $this->file->saveAs($head_image_file_path);
            $usermodel->headimage = $head_image_file_path;
            $usermodel->save();
            return true;
        }
        else
        {
            return false;
        }
    }
}