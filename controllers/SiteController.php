<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\RegisterForm;
use app\models\RegisterForm2;
use app\models\RegisterForm_school;
use app\models\EdituserdataForm;
use app\models\User;
use app\models\School;
use app\models\Wish;
use app\models\Banji;
use app\models\RelationshipBanjiMates;
use app\models\Message;
use app\models\Trade;
use app\models\UploadHeadImage;
use app\models\UploadCertificate;
use app\models\ForgotForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [
                    'logout',
                    'appcenter',
                    'register',
                    'register2',
                    'register_school',
                ],
                'rules' => [
                    [
                        'actions' => ['logout','appcenter'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['register','register2','register_school'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionRegister()
    {
        $model = new RegisterForm;

        if($model->load(Yii::$app->request->post()) && $model->beforSubmit())
        {
            $user = User::findOne(['email'=>$model->email]);
            $user->username = $model->username;
            $user->password = password_hash($model->password, PASSWORD_DEFAULT);//给密码进行哈希加密
            $user->school = (int)$model->schoolid;
            $user->tel = $model->tel;
            $user->isVerfied = 1;//1表示已验证
            $user->degree = 'vip';
            $user->headimage = './upload_user/demo/man.png';
            $user->money = 0;
            $user->register_time = date("Y-m-d H:i:s");
            $user->verifyCode = null;
            $user->save();
            return $this->render('registersucceed');
        }
        $allschool = School::findAllSchool();

        return $this->render('register',[
            'model'=>$model,
            'allschool'=>$allschool,
        ]);
    }

    public function actionRegister_school()
    {
        $model = new RegisterForm_School;
        
        if($model->load(Yii::$app->request->post()) && $model->beforSubmit())
        {
            $user = User::findOne(['email'=>$model->email]);
            $user->username = $model->username;
            $user->password = password_hash($model->password, PASSWORD_DEFAULT);
            $user->tel = $model->tel;
            $user->isVerfied = 1;
            $user->degree = 'witness';
            $user->headimage = './upload_user/demo/man.png';
            $user->money = 0;
            $user->register_time = date("Y-m-d H:i:s");
            $user->verifyCode = null;

            $school = new School;
            $school->witnessid = $user->id;
            $school->registerresult = 0;
            $school->minpercent = 10;
            $school->registername = $model->schoolname;
            $school->registertime = date("Y-m-d H:i:s");
            $school->save();

            $user->audit_school = $school->id;
            $user->school = $school->id;
            $user->save();

            return $this->render('registersucceed');
        }
        return $this->render('register',['model'=>$model]);
    }

    public function actionGet_verify_code()
    {
        $type = $_GET['type'];
        $email = $_POST['email'];
        $user = User::findOne(['email'=>$email]);
        switch($type)
        {
            case 'register':
                if($user)
                {
                    if($user->isVerfied == 1)
                    {
                        $status = 1;//邮箱已被注册
                    }
                    else
                    {
                        $user->updateVerifyCode();
                        if($user->sendVerifyCode()){$status = 0;}
                        else{$status = 2;}
                    }
                }
                else
                {
                    $user = new User;
                    $user->email = $email;
                    $user->updateVerifyCode();
                    if($user->sendVerifyCode()){$status = 0;}
                    else{$status = 2;}
                }
                break;
            case 'forgot':
                if($user)
                {
                    $user->updateVerifyCode();
                    if($user->sendVerifyCode_Forgot()){$status = 0;}
                    else{$status = 2;}
                }
                else
                {
                    $status = 1;//邮箱不存在
                }
                break;
            default:
                $data = null;
                break;
        }
        $data = [
            'status'=>$status,
        ];
        return json_encode($data);
        
    }

    public function actionAppcenter()
    {
        $user = Yii::$app->user->identity;

        if($user->degree == "vip")
        {
            $count = [
                'wish' => Wish::getMyWishCount($user->id),//我的心愿
                'banji' => Banji::getMyBanjiCount($user->id),//创建的团体
                'message' => count(Message::findAll(['toWho'=>$user->id])),//我的站内消息
                'donate' => count(Wish::findAll(['fromWho'=>$user->id])),//我的资助
                'join' => count(RelationshipBanjiMates::findAll(['mates'=>$user->id])),//加入的团体
            ];

            $trade = Trade::find()->where(['toWho'=>$user->id])->orWhere(['fromWho'=>$user->id])->orderBy(['tradeTime'=>SORT_DESC])->all();
            foreach ($trade as $value)
            {
                //如果类型是转账且自己是转账给别人的那个，或者类型为提现，则金额设为负值以表示支出
                if(($value->type == 1 && $value->fromWho == $user->id) || $value->type == 3)
                {
                    $value->money = -$value->money;
                }
            }
            $provider = new \yii\data\ArrayDataProvider([
                            'allModels' => $trade,
                            'pagination' => ['pageSize' => 5],
                            //'key' => 'id',
                        ]);

            return $this->render('appcenter',[
                'user' => $user,
                'count' => $count,
                'provider'=>$provider,
            ]);
        }
        else if($user->degree == 'admin')
        {
            $count = [
                'status0' => count(School::findAll(['registerresult'=>0])),//待审批
                'status1' => count(School::findAll(['registerresult'=>1])),//审核通过
                'status2' => count(School::findAll(['registerresult'=>2])),//审核不通过
                'message' => count(Message::findAll(['toWho'=>$user->id])),//我的站内消息
            ];

            $affair = [
                'apply_school' => School::findAll(['registerresult'=>0]),
            ];
            foreach($affair['apply_school'] as $value)
            {
                $value->witnessid = User::findOne(['id'=>$value->witnessid])->username;
            }
            $provider = new \yii\data\ArrayDataProvider([
                            'allModels' => $affair,
                            'pagination' => ['pageSize' => 10],
                            //'key' => 'id',
                        ]);

            return $this->render('appcenter_admin',[
                'user'=>$user,
                'count'=>$count,
                'provider'=>$provider,
            ]);
        }
        else if($user->degree == 'witness')
        {
            $count = [
                'result0' => count(Wish::findAll(['result'=>0,'school'=>$user->audit_school])),//待审核
                'result1' => count(Wish::findAll(['result'=>1,'school'=>$user->audit_school])),//审核通过
                'result2' => count(Wish::findAll(['result'=>[2,3],'school'=>$user->audit_school])),//审核不通过
                'status1' => count(Wish::findAll(['status'=>1,'school'=>$user->audit_school])),//待定资助周期
                'status3' => count(Wish::findAll(['status'=>3,'school'=>$user->audit_school])),//资助进行中
                'status4' => count(Wish::findAll(['status'=>4,'school'=>$user->audit_school])),//资助完成
                'message' => count(Message::findAll(['toWho'=>$user->id])),//我的站内消息
            ];

            $affair = [];
            $provider = new \yii\data\ArrayDataProvider([
                            'allModels' => $affair,
                            'pagination' => ['pageSize' => 10],
                            'key' => 'id',
                        ]);

            return $this->render('appcenter_witness',[
                'user'=>$user,
                'count'=>$count,
                'provider'=>$provider,
            ]);
        }
    }

    public function actionGetcurrentuserdata()
    {
        $model = Yii::$app->user->identity;
        $SingleViewModel = $model->getSingleView();
        $SingleViewModel['school'] = School::findOne(['id'=>$SingleViewModel['school']])->name;
        return $this->render('singleview',[
            'SingleView' => $SingleViewModel,
            'model' => $model,
        ]);
    }

    public function actionEdituserdata()
    {
        $model = Yii::$app->user->identity;
        $editform = new EdituserdataForm();
        $editform->nickname = $model->nickname;//真名
        $editform->sex = $model->sex;
        $editform->username = $model->username;
        $editform->email = $model->email;
        $editform->tel = $model->tel;
        $editform->id = $model->id;
        //$editform->avatar_show = $model->avatar_show; //库中暂时无此字段，暂时阉割此功能

        if ($editform->load(Yii::$app->request->post()))
        {
            $editform->upside_of_idcard = UploadedFile::getInstance($editform,'upside_of_idcard');
            $editform->downside_of_idcard = UploadedFile::getInstance($editform,'downside_of_idcard');
            if (!$editform->update())
            {
                return $this->render('singleview',['model' => $model, 'editform' => $editform]);
            }
            return $this->redirect(['getcurrentuserdata']);
        }

        return $this->render('singleview',[
            'model'=>$model,
            'editform'=>$editform,
        ]);
    }

    public function actionGetuserdata()
    {
        if(!$username = Yii::$app->request->get('username')){$data = null;}
        if($username == Yii::$app->user->identity->username){$data = null;}
        else if($user = User::findOne(['username'=>$username]))
        {
            $data = [
            'username'=>$user->username,
            'tel'=>isset($user->tel) ? $user->tel : '未设置',
            'email'=>$user->email,
            ];
        }
        else
        {
            $data = null;
        }

        return json_encode($data);
    }

    public function actionUploadheadimage()
    {
        $user = Yii::$app->user->identity;
        $model = new UploadHeadImage;
        $model->userid = $user->id;
        if($model->load($_POST) && $model->file = UploadedFile::getInstance($model,'file'))
        {
            if($model->validate() && $model->upload())
            {
                Yii::$app->session->setFlash('PictureUploaded');
            }
        }
        return $this->render('uploadheadimage', ['model' => $model]);
    }

    public function actionUploadcertificate()
    {
        $user = Yii::$app->user->identity;
        $model = new UploadCertificate;
        $model->userid = $user->id;
        if($model->load($_POST) && $model->file = UploadedFile::getInstance($model,'file'))
        {
            if($model->validate() && $model->upload())
            {
                Yii::$app->session->setFlash('PictureUploaded');
            }
        }
        return $this->render('uploadheadimage', ['model' => $model]);
    }

    public function actionForgot()
    {
        $forgotForm = new ForgotForm;
        if($forgotForm->load(Yii::$app->request->post()) && $forgotForm->validate())
        {
            $user = User::findOne(['email'=>$forgotForm->email]);
            $user->verifyCode = null;
            $user->updatePassword($forgotForm->password);
            Yii::$app->session->setFlash('Succeed');
        }
        return $this->render('forgot',['model'=>$forgotForm]);
    }
}
