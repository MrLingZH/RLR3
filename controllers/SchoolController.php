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
                    'setminpercent',
                    'updatebywitness',
                ],
                'rules' => [
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['setminpercent','updatebywitness'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule,$action)
                        {
                            $user = Yii::$app->user->identity;
                            return $user->isWitness();
                        }
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

    public function actionSetminpercent()
    {
        $user = Yii::$app->user->identity;
        $school = School::findOne(['witnessid'=>$user->id]);   
        if($school->load(Yii::$app->request->post()) && $school->save())
        {       
            Yii::$app->session->setFlash('SetSchoolMinpercentSuccess');
        }
        return $this->render('setminpercent', [       
            'model' => $school,       
        ]);       
    }

    public function actionUpdatebywitness()
    {
        $model = School::findOne(['witnessid'=>Yii::$app->user->identity->id]);

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect(['view','id'=>$model->id]);
        }
        return $this->render('updatebywitness',[
            'model' => $model,
        ]);
    }
}
?>