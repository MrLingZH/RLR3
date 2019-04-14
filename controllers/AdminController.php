<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\School;
use app\models\User;

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
		if($school->save() && $user->save())
		{
			return $this->render('succeed');
		}
		return $this->render('failed');
	}
}


?>