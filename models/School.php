<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "school".
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property int $minpercent 戮猫驴卯脳卯脨隆掳脵路脰卤脠
 * @property string $pictures
 * @property int $registerresult 脳垄虏谩陆谩鹿没拢卢0=麓媒脡贸潞脣拢卢1=脡贸潞脣脥篓鹿媒拢卢2=脡贸潞脣虏禄脥篓鹿媒
 * @property string $subDomain 露镁录露脫貌脙没
 * @property string $type
 * @property int $witnessid 
 * @property string $registertime
 * @property string $registername 脡锚脟毛脢卤碌脠麓媒脡贸潞脣碌脛脕脵脢卤脩搂脨拢脙没
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
