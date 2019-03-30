<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $sex
 * @property string $degree
 * @property string $headimage
 * @property int $isVerfied
 * @property int $money
 * @property int $audit_school
 * @property int $reg_school
 * @property int $school
 * @property string $tel
 * @property string $idcard_upside
 * @property string $idcard_downside
 * @property string $register_time
 * @property string $verifyCode
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['isVerfied', 'money', 'audit_school', 'reg_school', 'school'], 'integer'],
            [['register_time'], 'safe'],
            [['username', 'email', 'password', 'sex', 'degree', 'headimage', 'tel', 'idcard_upside', 'idcard_downside', 'verifyCode'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'sex' => 'Sex',
            'degree' => 'Degree',
            'headimage' => 'Headimage',
            'isVerfied' => 'Is Verfied',
            'money' => 'Money',
            'audit_school' => 'Audit School',
            'reg_school' => 'Reg School',
            'school' => 'School',
            'tel' => 'Tel',
            'idcard_upside' => 'Idcard Upside',
            'idcard_downside' => 'Idcard Downside',
            'register_time' => 'Register Time',
            'verifyCode' => 'Verify Code',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    public static function findIdentityByAccessToken($token,$type=null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }
    
    public function getAuthKey()
    {
        return "";
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public static function findByUsername($username)
    {
        return static::find()->where(['username'=>$username])->one();
    }
    
    public static function findByEmail($email)
    {
        return static::find()->where(['email'=>$email])->one();
    }

    public function validatePassword($password)
    {
        return password_verify($password,$this->password);//哈希比较，输入的密码与数据库中的哈希加密过的密码进行比较。
    }
}
