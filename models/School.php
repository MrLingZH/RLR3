<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "school".
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property int $minpercent 捐款最小百分比
 * @property string $pictures
 * @property int $registerresult 注册结果，0=待审核，1=审核通过，2=审核不通过
 * @property string $subDomain 二级域名
 * @property string $type
 * @property int $witnessid 
 * @property string $registertime
 * @property string $registername 申请时等待审核的临时学校名
 * @property string $schoolnumber 学校代码
 * @property string $foundtime 通过审核正式成立的时间
 * @property string $disagreedreason 拒绝理由
 */
class School extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'school';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['minpercent', 'registerresult', 'witnessid'], 'integer'],
            [['registertime','foundtime'], 'safe'], 
            [['name', 'address', 'pictures', 'subDomain', 'type', 'registername','schoolnumber','disagreedreason'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'address' => '地址',
            'minpercent' => '资金百分比',
            'pictures' => '照片',
            'registerresult' => '注册结果',
            'subDomain' => '二级域名',
            'type' => '类型',
            'witnessid' => '见证人',
            'registertime' => '注册时间', 
            'registername' => '注册名称',
            'schoolnumber' => '代码',
            'foundtime' => '创立时间',
            'disagreedreason' => '审核不通过理由',
        ];
    }

    public static function findAllSchool()
    {
        return static::findAll(['registerresult'=>1]);
    }

    public static function findById($id)
    {
        return static::findOne(['id'=>$id]);
    }

    public static function findByName($name)
    {
        return static::findOne(['name'=>$name]);
    }

    public static function findBySubDomain($subDomain)
    {
        return static::findOne(['subDomain'=>$subDomain]);
    }

    public static function getWitnessid($id)
    {
        $result = static::findOne(['id'=>$id]);

        return $result->witnessid;
    }

    public function getSubDomain()
    {
        $code = '0123456789';
        while(1)
        {
            $this->subDomain = 's';
            $this->subDomain .= $code{mt_rand(1,9)};
            for($i=1;$i<=4;$i++)
            {
                $this->subDomain .= $code{mt_rand(0,9)};
            }
            if(!School::findBySubDomain($this->subDomain))break;
        }
    }

    public function getSchoolnumber()
    {
        $code = '0123456789';
        while(1)
        {
            $this->schoolnumber = $code{mt_rand(1,9)};
            for($i=1;$i<=4;$i++)
            {
                $this->schoolnumber .= $code{mt_rand(0,9)};
            }
            if(!School::findBySubDomain($this->subDomain))break;
        }
    }
}
