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
 * @property int $registerresult
 * @property string $subDomain 二级域名
 * @property string $type
 * @property int $witnessid 
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
            [['name', 'address', 'pictures', 'subDomain', 'type'], 'string', 'max' => 255],
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
        ];
    }

    public static function findAllSchool()
    {
        return static::find()->all();
    }

    public static function getWitnessid($id)
    {
        $result = static::findOne(['id'=>$id]);

        return $result->witnessid;
    }
}
