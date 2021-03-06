<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\School;
use app\models\User;
use app\models\SimpleForm;
use app\models\Message;

class AdminController extends Controller
{
	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [
                    'agreed_apply_school',
                    'disagreed_apply_school',
                ],
                'rules' => [
                    [
                        'actions' => ['agreed_apply_school','disagreed_apply_school'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule,$action)
                        {
                            $user = Yii::$app->user->identity;
                            return $user->isAdmin();
                        }
                    ],
                ],
            ],
        ];
    }

	public function actionAgreed_apply_school()
	{
		$school = School::findOne(Yii::$app->request->get('id'));
		$school->name = $school->registername;
		$school->registerresult = 1;
		$school->getSubDomain();
		$school->getSchoolnumber();
		$school->foundtime = date("Y-m-d H:i:s");

		$user = User::findOne($school->witnessid);
		$user->degree = 'witness';
		if($school->save())
		{
			if($user->save())
			{
				$message = new Message;
				$message->fromWho = Yii::$app->user->identity->id;
				$message->toWho = $school->witnessid;
				$message->title = "学校注册申请结果";
				$message->content = "您于".$school->registertime."申请的“学校注册”已通过审核！";
				$message->status = 0;
				$message->sendTime = date("Y-m-d H:i:s");
				$message->send();
				return $this->render('succeed');
			}
			$school->name = null;
			$school->registerresult = 0;
			$school->subDomain = null;
			$school->schoolnumber = null;
			$school->foundtime = null;
			$school->save();
			return $this->render('failed',['status'=>1]);
		}
		return $this->render('failed',['status'=>0]);
	}

	public function actionDisagreed_apply_school()
	{
		$model = new SimpleForm;

		if($model->load(Yii::$app->request->post()))
		{
			$school = School::findOne(Yii::$app->request->get('id'));
			$school->registerresult = 2;
			$school->disagreedreason = $model->reason;
			if($school->save())
			{
				$message = new Message;
				$message->fromWho = Yii::$app->user->identity->id;
				$message->toWho = $school->witnessid;
				$message->title = "学校注册申请结果";
				$message->content = "您于".$school->registertime."申请的“学校注册”已被拒绝，拒绝理由为：".$school->disagreedreason;
				$message->status = 0;
				$message->sendTime = date("Y-m-d H:i:s");
				if($message->send())
				{
					return $this->render('succeed');
				}
				$school->registerresult = 0;
				$school->disagreedreason = null;
				$school->save();
				return $this->render('failed',['status'=>1]);
			}
			return $this->render('failed',['status'=>0]);
		}

		return $this->render('disagreed',['model'=>$model]);
	}

	public function actionSupply_list()
	{
		$result = Yii::$app->request->get('result');
		if(!($result >= 0 && $result <= 2))return $this->redirect(['site/appcenter']);
		switch($result)
		{
			case 0:
				$school = School::find()->where(['registerresult'=>0])->orderBy(['registertime'=>SORT_DESC])->all();
				$title = '待审核';
				break;
			case 1:
				$school = School::find()->where(['registerresult'=>1])->orderBy(['registertime'=>SORT_DESC])->all();
				$title = '审核通过';
				break;
			case 2:
				$school = School::find()->where(['registerresult'=>2])->orderBy(['registertime'=>SORT_DESC])->all();
				$title = '审核未通过';
				break;
		}
		
        $provider = new \yii\data\ArrayDataProvider([
                            'allModels' => $school,
                            'pagination' => ['pageSize' => 10],
                            'key' => 'id',
                        ]);
		return $this->render('supply_list',[
			'provider'=>$provider,
			'title0'=>$title,
		]);
	}
}


?>