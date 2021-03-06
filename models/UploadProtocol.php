<?php


namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;
use app\models\User;
use yii\validators\FileValidator;

class UploadProtocol extends Model
{
    /**
     * @var UploadedFile
     */
    public $userid;
    public $wishid;
    public $file;

    const MBYTES = 1048576;//1MB=1024*1024B

    public function rules()
    {
        return [
            [['file'], 'file', 'extensions' => 'png, jpg', 'maxSize' => 2 * self::MBYTES],
        ];
    }

    public function attributeLabels()
    {
        return [
            'file'=>'文件',
        ];
    }

    public function upload()
    {
        if($this->validate())
        {
            $usermodel = User::findOne(['id'=>$this->userid]);
            $folder_path = './upload_user/'.$usermodel->email.'/protocol';
            //文件直接以对应心愿的id命名
            $file_path = $folder_path.'/'.$this->wishid.'.'.$this->file->extension;
            if (!file_exists($folder_path))
            {
                mkdir($folder_path, 0777, true);
            }
            $this->file->saveAs($file_path);
            return true;
        }
        else
        {
            return false;
        }
    }
}