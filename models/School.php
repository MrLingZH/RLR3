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
            [['registertime'], 'safe'], 
            [['name', 'address', 'pictures', 'subDomain', 'type', 'registername'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'address' => 'Address',
            'minpercent' => 'Minpercent',
            'pictures' => 'Pictures',
            'registerresult' => 'Registerresult',
            'subDomain' => 'Sub Domain',
            'type' => 'Type',
            'witnessid' => 'Witnessid',
            'registertime' => 'Registertime', 
            'registername' => 'Registername',
        ];
    }

    public static function findAllSchool()
    {
        return static::find()->all();
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
}
