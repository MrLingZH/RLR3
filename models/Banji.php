<?php

namespace app\models;

use Yii;
use app\models\RelationshipBanjiMates;

/**
 * This is the model class for table "banji".
 *
 * @property int $id
 * @property string $name
 * @property int $administrator
 * @property int $school
 * @property int $money
 * @property string $token 特征码
 * @property string $createtime
 */
class Banji extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'banji';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['administrator', 'school', 'money'], 'integer'],
            [['createtime'], 'safe'],
            [['name', 'token'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '班级名称',
            'administrator' => '班级管理员',
            'school' => '所属社区',
            'money' => '金额',
            'token' => '特征码',
            'createtime' => '创建时间',
        ];
    }

    public static function findById($id)
    {
        return static::findOne(['id'=>$id]);
    }

    public static function findByName($name)
    {
        return static::findOne(['name'=>$name]);
    }

    public static function findByToken($token)
    {
        return static::findOne(['token'=>$token]);
    }

    public static function getIdByName($name)
    {
        if($result = static::findOne(['name'=>$name]))
        {
            return $result->id;
        }
        return false;
    }

    public static function getMyBanjiCount($id)
    {
        return count(static::findAll(['administrator'=>$id]));
    }

    public static function getMybanji($id)
    {
        return static::findAll(['administrator'=>$id]);
    }

    //判断用户id是否为该班级的成员
    public function isMate($userid)
    {
        $mates = RelationshipBanjiMates::findAll(['banji'=>$this->id]);
        foreach($mates as $v)
        {
            if($userid == $v->mates)
            {
                return true;
            }
        }
        return false;
    }

    public function isAdministrator($userid)
    {
        if($this->administrator == $userid)return true;
        return false;
    }
}
