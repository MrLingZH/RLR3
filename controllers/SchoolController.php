<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\School;
use app\models\User;

class SchoolController extends Controller
{
	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [
                    'view',
                ],
                'rules' => [
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

	public function actionView()
	{
		if(!$school = School::findOne(['id'=>Yii::$app->request->get('id')]))return $this->redirect(['site/appcenter']);
		return $this->render('view',[
			'school'=>$school,
		]);
	}
}
?>