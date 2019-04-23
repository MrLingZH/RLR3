<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\School;
use app\models\User;
use app\models\SimpleForm;
use app\models\Message;

class AdminController extends Controller
{
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
}


?>