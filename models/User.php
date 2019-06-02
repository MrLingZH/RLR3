<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $nickname
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
    public $trans_sex = [
        'man'=>'男',
        'woman'=>'女',
        null=>'未知',
    ];

    public $trans_verify = [
        0=>'未验证',
        1=>'已验证',
        null=>'未知',
    ];
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
            [['username','nickname', 'email', 'password', 'sex', 'degree', 'headimage', 'tel', 'idcard_upside', 'idcard_downside', 'verifyCode'], 'string', 'max' => 255],
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
            'username' => 'Nickname',
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

    public function updateVerifyCode()
    {
        $this->verifyCode = (string)mt_rand(10000,99999);
        $this->save();
    }

    public function sendVerifyCode()
    {
        $to = $this->email;
        $subject = "《人恋人公益平台》注册码";
        $body = "亲爱的".$this->email."您好，这是您的注册验证码：".$this->verifyCode."。感谢您的注册！";

        $mail = Yii::$app->mailer->compose(); //加载配置的组件
        $mail->setTo($to); //要发给谁
        $mail->setSubject($subject); //标题 主题
        $mail->setHtmlBody($body); //要发送的内容
        
        return $mail->send();
    }
    public function sendVerifyCode_Forgot()
    {
        $to = $this->email;
        $subject = "《人恋人公益平台》密码找回验证码";
        $body = "亲爱的".$this->email."您好，这是您的密码找回验证码：".$this->verifyCode."。如果这不是您本人的操作，请注意您的账号安全,切勿将验证码泄露！";

        $mail = Yii::$app->mailer->compose();
        $mail->setTo($to);
        $mail->setSubject($subject);
        $mail->setHtmlBody($body);
        
        return $mail->send();
    }

    public function getSingleView()
    {
        $SingleViewResult = array(
            'id'=>$this->id,
            'username' => $this->username,
            'nickname' => $this->nickname,
            'email' => $this->email,
            'degree'=> $this->degree,
            'money' => $this->money,
            'audit_school' => $this->audit_school,
            'reg_school' => $this->reg_school,
            'school' => $this->school,
            'register_time' => $this->register_time,
            //'register'=>$this->register,
            //'help'=>$this->help,
            'idcard_upside' => $this->idcard_upside,
            'idcard_downside' => $this->idcard_downside,
            'sex'=>isset($this->sex)? $this->trans_sex[$this->sex] : '未设置',
            'tel'=>isset($this->tel)? $this->tel : '未设置',
            'isVeified'=>$this->trans_verify[$this->isVerfied],
            );
        return $SingleViewResult;
    }

    public function updatePassword($psw)
    {
        $this->password = password_hash($psw, PASSWORD_DEFAULT);
        $this->save();
    }

    public function isWitness()
    {
        if($this->degree == 'witness')return true;
        return false;
    }

    public function isAdmin()
    {
        if($this->degree == 'admin')return true;
        return false;
    }
}
