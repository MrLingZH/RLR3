<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\RegisterForm;
use app\models\RegisterForm2;
use app\models\RegisterForm_school;
use app\models\User;
use app\models\School;
use app\models\Wish;
use app\models\Banji;

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
                    'register_school',
                ],
                'rules' => [
                    [
                        'actions' => ['logout','appcenter','register_school'],
                        'allow' => true,
                        'roles' => ['@'],
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
        if(!Yii::$app->user->isGuest){return $this->goBack();}

        $model = new RegisterForm;

        if($model->load(Yii::$app->request->post()))
        {
            $user = User::findByEmail($model->email);;
            //查询该邮箱用户是否存在，如果该邮箱用户不存在，则添加到用户表，并生成验证码发送邮件
            if($user == null)
            {
                $user = new User;
                $user->email = $model->email;
                $user->isVerfied = 0;
                $user->verifyCode = $model->getVerifyCode();
                $user->save();
                
                //这里是发邮件代码
                if(!$model->sendEmail())
                {
                    return $this->render('registerfailed',['status'=>2]);
                }
            }
            //如果存在且验证状态为1，则已被注册,否则未验证，更新验证码并重新发送邮件
            if($user)
            {
                if($user->isVerfied == 1)
                {
                    return $this->render('registerfailed',['status'=>1]);
                }
                $user->verifyCode = $model->getVerifyCode();
                $user->save();

                //这里是发邮件代码
                if(!$model->sendEmail())
                {
                    return $this->render('registerfailed',['status'=>2]);
                }
            }

            return $this->redirect(['site/register2','email'=>$model->email]);//执行本控制器中的Actionregister2,并将email传过去
        }
        return $this->render('register',[
            'model'=>$model,
        ]);
    }

    public function actionRegister2()
    {
        if(!Yii::$app->user->isGuest){return $this->goBack();}

        $model = new RegisterForm2;

        $model->email = Yii::$app->request->get('email');//redirect实现控制器间的转跳，但method只能是get

        if(!$model->email){return $this->redirect(['site/register']);}//检测第一步是否已提交邮箱，没有则返回第一步

        if($model->load(Yii::$app->request->post()) && $model->register())
        {
            $user = User::findByEmail($model->email);
            $user->username = $model->username;
            $user->password = password_hash($model->password, PASSWORD_DEFAULT);//给密码进行哈希加密
            $user->school = (int)$model->schoolid;
            $user->tel = $model->tel;
            $user->isVerfied = 1;//1表示已验证
            $user->degree = 'vip';
            $user->headimage = './upload_user/demo/man.png';
            $user->money = 0;
            $user->register_time = date("Y-m-d H:i:s");
            $user->save();

            return $this->render('registersucceed');
        }

        $allschool = School::findAllSchool();

        return $this->render('register2',[
            'model'=>$model,
            'allschool'=>$allschool,
        ]);
    }

    public function actionAppcenter()
    {
        $user = Yii::$app->user->identity;

        if($user->degree == "vip")
        {
            $count = [
                'wish' => Wish::getMyWishCount($user->id),//我的心愿
                'banji' => Banji::getMyBanjiCount($user->id),//创建的团体
                'message' => 0,//我的站内消息
                'donate' => 0,//我的资助
                'join' => 0,//加入的团体
            ];

            //交易记录表还没建立，下面为临时数据
            $trade = [
                '0' => [
                    'id' => 0,
                    'tradetime' => '2019-4-12 22:36:46',
                    'type' => '扣钱',
                    'money' => -100,
                    'status' => '成功',
                ],
                '1' => [
                    'id' => 1,
                    'tradetime' => '2019-4-12 22:46:18',
                    'type' => '充钱',
                    'money' => 648,
                    'status' => '成功',
                ],
            ];
            $provider = new \yii\data\ArrayDataProvider([
                            'allModels' => $trade,
                            'pagination' => ['pageSize' => 10],
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
                'message' => 0,//我的站内消息
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
    }

    public function actionRegister_school()
    {
        //判断是否已经是学校的见证人，如果是，驳回申请。
        if(Yii::$app->user->identity->degree == 'witness' || Yii::$app->user->identity->degree == 'admin')
        {
            return $this->render('registerfailed_school',['status'=>3]);
        }

        $model = new RegisterForm_school;
        
        if($model->load(Yii::$app->request->post()) && $model->beforSubmit())
        {
            if($school = School::findOne(['witnessid'=>Yii::$app->user->identity->id,'registerresult'=>0]))
            {
                //如果能查询到状态为待审核的数据，则更新那条数据
            }
            else
            {
                //否则新建一条数据
                $school = new School;
                $school->witnessid = Yii::$app->user->identity->id;
                $school->registerresult = 0;
                $school->minpercent = 10;
            }
            $school->registername = $model->name;
            $school->registertime = date("Y-m-d H:i:s");
            if($school->save())
            {
                return $this->render('registersucceed_school');
            }
            else
            {
                return $this->render('registerfailed_school',['status'=>2]);
            }
        }

        return $this->render('register_school',['model'=>$model]);
    }
}
