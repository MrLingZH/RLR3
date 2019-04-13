<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\WishForm;
use app\models\School;
use app\models\Wish;
use app\models\User;

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
			$wish->result = 0;
			$wish->status = 0;
			$wish->guardian_name = $model->guardian_name;
			$wish->guardian_tel = $model->guardian_tel;
			$wish->guardian_cardnumber = $model->guardian_cardnumber;
			$wish->tag = $model->tag;
			$wish->wishtime = date("Y-m-d H:i:s");
			if(!$wish->save()){return $this->render('wishfailed',['status'=>2]);}

			return $this->render('wishsucceed');
		}

		$allschool = School::findAllSchool();

		return $this->render('Wish',[
			'model'=>$model,
			'allschool'=>$allschool,
		]);
	}

	public function actionMywish()
	{
		if($mywish = Wish::findAll(['toWho'=>Yii::$app->user->identity->id]))
		{
			foreach($mywish as $value)
			{
				$value->toWho = User::findOne(['id'=>$value->toWho])->username;
				$value->auditor = User::findOne(['id'=>$value->auditor])->username;
				$value->school = School::findOne(['id'=>$value->school])->name;
			}
		}

		$provider = new \yii\data\ArrayDataProvider([
                        'allModels' => $mywish,
                        'pagination' => ['pageSize' => 10],
                        'key' => 'id',
                    ]);

		return $this->render('mywish',[
			'provider'=>$provider,
		]);
	}

	public function actionWishdetail()
	{
		$wish = Wish::findOne(['id'=>Yii::$app->request->get('id')]);
		$wish->auditor = User::findOne(['id'=>$wish->auditor])->username;

		$toWho = User::findOne(['id'=>$wish->toWho]);

		$data = [
			'progress'=>'暂无',
		];
		return $this->render('wishdetail',[
			'wish'=>$wish,
			'toWho'=>$toWho,
			'data'=>$data,
		]);
	}

	public function actionEditwish()
	{
		$wish = Wish::findOne(['id'=>Yii::$app->request->get('id')]);

		//防止在修改期间恰逢被审核通过导致错误覆盖,因为审核通过后不能再修改
		if($wish->status != 0)
		{
			return $this->render('error',['message'=>'请求失败！该心愿的审核状态已发生改变，若返回查看页面后未发生变化，请刷新页面。']);
		}

		$model = new WishForm;
		$model->count = $wish->count;
		$model->totalMoney = $wish->totalMoney;
		$model->tag = $wish->tag;
		$model->schoolid = $wish->school;
		$model->schoolnumber = School::findOne(['id'=>$wish->school])->schoolnumber;
		$model->guardian_name = $wish->guardian_name;
		$model->guardian_tel = $wish->guardian_tel;
		$model->guardian_cardnumber = $wish->guardian_cardnumber;
		$model->description = $wish->description;

		if($model->load(Yii::$app->request->post()) && $model->wish())
		{
			$wish->auditor = School::getWitnessid($model->schoolid);
			$wish->school = $model->schoolid;
			$wish->count = $model->count;
			$wish->description = $model->description;
			$wish->totalMoney = $model->totalMoney;
			$wish->installment = 'monthly';
			$wish->guardian_name = $model->guardian_name;
			$wish->guardian_tel = $model->guardian_tel;
			$wish->guardian_cardnumber = $model->guardian_cardnumber;
			$wish->tag = $model->tag;
			$wish->wishtime = date("Y-m-d H:i:s");
			if(!$wish->save()){return $this->render('error',['message'=>'请求失败！']);}

			//return $this->redirect(['donate/wishdetail','id'=>$wish->id]);
			return $this->render('editsucceed',[
				'id'=>$wish->id,
			]);
		}

		$allschool = School::findAllSchool();

		return $this->render('wish',[
			'model'=>$model,
			'allschool'=>$allschool,
		]);
	}

}


?>