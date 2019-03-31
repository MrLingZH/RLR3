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
use app\models\User;
use app\models\School;

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
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
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
                /*
                这里是发邮件代码
                */
            }
            //如果存在且验证状态为1，则已被注册,否则未验证，更新验证码并重新发送邮件
            if($user)
            {
                if($user->isVerfied == 1)
                {
                    return $this->render('registerfailed');
                }
                $user->verifyCode = $model->getVerifyCode();
                $user->save();
                /*
                这里是发邮件代码
                */
            }

            return $this->redirect(['site/register2','email'=>$model->email]);//执行本控制器中的Actionregister2,并将email传过去
        }
        return $this->render('register',[
            'model'=>$model,
        ]);
    }

    public function actionRegister2()
    {
        $model = new RegisterForm2;

        $model->email = Yii::$app->request->get('email');//redirect实现控制器间的转跳，但method只能是get

        if($model->load(Yii::$app->request->post()) && $model->register())
        {
            $user = User::findByEmail($model->email);
            $user->username = $model->username;
            $user->password = password_hash($model->password, PASSWORD_DEFAULT);//给密码进行哈希加密
            $user->reg_school = $model->schoolid;
            $user->tel = $model->tel;
            $user->isVerfied = 1;//1表示已验证
            $user->save();

            return $this->render('registersucceed');
        }

        $allschool = School::findAllSchool();

        return $this->render('register2',[
            'model'=>$model,
            'allschool'=>$allschool,
        ]);
    }
}
