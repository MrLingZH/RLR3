<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\WishForm;
use app\models\School;
use app\models\Wish;

class DonateController extends Controller
{
	public function actionWish()
	{
		$model = new WishForm;

		if($model->load(Yii::$app->request->post()) && $model->wish())
		{
			if($model->schoolid == 0){return $this->render('wishfailed',['status'=>1]);}

			$wish = new Wish;
			$wish->toWho = Yii::$app->user->identity->id;
			$wish->auditor = School::getWitnessid($model->schoolid);
			$wish->school = $model->schoolid;
			$wish->count = $model->count;
			$wish->description = $model->description;
			$wish->totalMoney = $model->totalMoney;
			$wish->installment = 'monthly';
			$wish->status = 0;
			$wish->guardian_name = $model->guardian_name;
			$wish->guardian_tel = $model->guardian_tel;
			$wish->guardian_cardnumber = $model->guardian_cardnumber;
			$wish->tag = $model->tag;
			$wish->wishtime = date("Y-m-d H:i:s");
			if(!$wish->save()){return $this->render('wishfailed',['status'=>2]);}

			return $this->render('wishsucceed',['data'=>$model]);
		}

		$allschool = School::findAllSchool();

		return $this->render('Wish',[
			'model'=>$model,
			'allschool'=>$allschool,
		]);
	}

}


?>