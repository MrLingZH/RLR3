<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\WishForm;
use app\models\School;

class DonateController extends Controller
{
	public function actionWish()
	{
		$model = new WishForm;

		if($model->load(Yii::$app->request->post()))
		{
			$model->count = (int)$_POST['count'];
			$model->tag = (int)$_POST['tag'];
			$model->schoolid = (int)$_POST['schoolid'];

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