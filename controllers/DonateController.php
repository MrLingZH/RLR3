<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\WishForm;
use app\models\School;
use app\models\Wish;
use app\models\User;
use app\models\SimpleForm;
use app\models\Message;

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
		if(!$wish = Wish::findOne(['id'=>Yii::$app->request->get('id')])){return $this->redirect(['donate/mywish']);}
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
		if(!$wish = Wish::findOne(['id'=>Yii::$app->request->get('id')])){return $this->redirect(['donate/mywish']);}
		if(!(Yii::$app->user->identity->id == $wish->toWho || Yii::$app->user->identity->audit_school == $wish->school)){return $this->redirect(['donate/mywish']);}

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
			'wish'=>$wish,
		]);
	}

	/*public function actionList_apply()
	{
		$wish = Wish::find()->where(['school'=>Yii::$app->user->identity->audit_school,'result'=>0])->orderBy(['wishtime'=>SORT_DESC])->all();
        foreach($wish as $value)
        {
            $value->toWho = User::findOne(['id'=>$value->toWho])->username;
            $value->school = School::findOne(['id'=>$value->school])->name;
        }
        $provider = new \yii\data\ArrayDataProvider([
                            'allModels' => $wish,
                            'pagination' => ['pageSize' => 10],
                            'key' => 'id',
                        ]);
		return $this->render('wish_supply_list',['provider'=>$provider]);
	}*/

	public function actionWish_supply_list()
	{
		$result = Yii::$app->request->get('result');
		$status = Yii::$app->request->get('status');
		$audit_school = Yii::$app->user->identity->audit_school;
		if($result == 0)
		{
			$wish = Wish::find()->where(['school'=>$audit_school,'result'=>0])->orderBy(['wishtime'=>SORT_DESC])->all();
			$title = '心愿申请';
		}
		else if($result == 1)
		{
			switch($status)
			{
				case 1:
					$wish = Wish::find()->where(['school'=>$audit_school,'result'=>1,'status'=>1])->orderBy(['wishtime'=>SORT_DESC])->all();
					$title = '待定资助周期';
					break;
				case 3:
					$wish = Wish::find()->where(['school'=>$audit_school,'result'=>1,'status'=>3])->orderBy(['wishtime'=>SORT_DESC])->all();
					$title = '资助进行中';
					break;
				case 4:
					$wish = Wish::find()->where(['school'=>$audit_school,'result'=>1,'status'=>4])->orderBy(['wishtime'=>SORT_DESC])->all();
					$title = '资助完成';
					break;
				default:
					$wish = Wish::find()->where(['school'=>$audit_school,'result'=>1])->orderBy(['wishtime'=>SORT_DESC])->all();
					$title = '审核通过';
					break;
			}
		}
		else
		{
			$wish = Wish::find()->where(['school'=>$audit_school,'result'=>[2,3]])->orderBy(['wishtime'=>SORT_DESC])->all();
			$title = '审核未通过';
		}
		
        foreach($wish as $value)
        {
            $value->toWho = User::findOne(['id'=>$value->toWho])->username;
            $value->school = School::findOne(['id'=>$value->school])->name;
        }
        $provider = new \yii\data\ArrayDataProvider([
                            'allModels' => $wish,
                            'pagination' => ['pageSize' => 10],
                            'key' => 'id',
                        ]);
		return $this->render('wish_supply_list',[
			'provider'=>$provider,
			'title0'=>$title,
		]);
	}

	public function actionWish_agreed()
	{
		$model = new SimpleForm;

		if($model->load(Yii::$app->request->post()))
		{
			$wish = Wish::findOne(['id'=>Yii::$app->request->get('id')]);
			$wish->result = 1;
			$wish->reason = $model->reason;
			if($wish->save())
			{
				$message = new Message;
				$message->fromWho = Yii::$app->user->identity->id;
				$message->toWho = $wish->toWho;
				$message->title = "助学申请审核结果";
				$message->content = "您于".$wish->wishtime."申请的“助学申请”已通过，理由为：".$wish->reason;
				$message->status = 0;
				$message->sendTime = date("Y-m-d H:i:s");
				if($message->send())
				{
					return $this->render('succeed');
				}
				$wish->result = 0;
				$wish->reson = null;
				$wish->save();
				return $this->render('failed',['status'=>1]);
			}
			return $this->render('failed',['status'=>0]);
		}

		return $this->render('reason',['model'=>$model]);
	}

	public function actionWish_disagreed()
	{
		$model = new SimpleForm;

		if($model->load(Yii::$app->request->post()))
		{
			$wish = Wish::findOne(['id'=>Yii::$app->request->get('id')]);
			$wish->result = 2;
			$wish->reason = $model->reason;
			if($wish->save())
			{
				$message = new Message;
				$message->fromWho = Yii::$app->user->identity->id;
				$message->toWho = $wish->toWho;
				$message->title = "助学申请审核结果";
				$message->content = "您于".$wish->wishtime."申请的“助学申请”已被拒绝，理由为：".$wish->reason;
				$message->status = 0;
				$message->sendTime = date("Y-m-d H:i:s");
				if($message->send())
				{
					return $this->render('succeed');
				}
				$wish->result = 0;
				$wish->reson = null;
				$wish->save();
				return $this->render('failed',['status'=>1]);
			}
			return $this->render('failed',['status'=>0]);
		}

		return $this->render('reason',['model'=>$model]);
	}

	public function actionWish_delete()
	{
		$model = new SimpleForm;

		if($model->load(Yii::$app->request->post()))
		{
			$wish = Wish::findOne(['id'=>Yii::$app->request->get('id')]);
			$wish->result = 3;
			$wish->reason = $model->reason;
			if($wish->save())
			{
				$message = new Message;
				$message->fromWho = Yii::$app->user->identity->id;
				$message->toWho = $wish->toWho;
				$message->title = "助学申请审核结果";
				$message->content = "您于".$wish->wishtime."申请的“助学申请”已被删除，理由为：".$wish->reason;
				$message->status = 0;
				$message->sendTime = date("Y-m-d H:i:s");
				if($message->send())
				{
					return $this->render('succeed');
				}
				$wish->result = 0;
				$wish->reson = null;
				$wish->save();
				return $this->render('failed',['status'=>1]);
			}
			return $this->render('failed',['status'=>0]);
		}

		return $this->render('reason',['model'=>$model]);
	}

}


?>