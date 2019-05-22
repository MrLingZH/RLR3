<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;
use app\models\Banji;
use app\models\Message;
use app\models\Trade;
use app\models\SimpleForm;

class MoneyController extends Controller
{
	public function actionRecharge()
	{
		$model = new SimpleForm;

		if($model->load(Yii::$app->request->post()))
		{
			$user = User::findOne(['id'=>Yii::$app->user->identity->id]);
			$trade = new Trade;
			$trade->money = $model->money;
			$trade->toWho = $user->id;
			$trade->way = Yii::$app->request->post('way');
			$trade->type = 2;//充值
			$trade->status = 1;//临时
			//$trade->flow = '';
			$trade->tradeTime = date('Y-m-d H:i:s');
			if($trade->save())
			{
				$user->money += $model->money;
				if($user->save())
				{
					return $this->render('succeed');
				}
				return $this->render('failed',['status'=>1]);
			}
			return $this->render('failed',['status'=>2]);
		}

		return $this->render('recharge',['model'=>$model]);
	}

	public function actionTransfertoperson()
	{
		$model = new SimpleForm;

		if($model->load(Yii::$app->request->post()))
		{
			$fromWho = User::findOne(['id'=>Yii::$app->user->identity->id]);
			$toWho = User::findOne(['username'=>$model->toWho]);
			$trade = new Trade;
			$trade->money = $model->money;
			$trade->toWho = $toWho->id;
			$trade->fromWho = $fromWho->id;
			$trade->way = 1;//用余额转账
			$trade->type = 1;//转账
			$trade->status = 1;//临时
			//$trade->transaction_id = '';
			$trade->tradeTime = date('Y-m-d H:i:s');
			if($trade->save())
			{
				$fromWho->money -= $model->money;
				$toWho->money += $model->money;
				if($fromWho->save())
				{
					if($toWho->save())
					{
						return $this->render('succeed');
					}
					$fromWho->money += $model->money;
					$fromWho->save();
					return $this->render('failed',['status'=>3]);
				}
				return $this->render('failed',['status'=>1]);
			}
			return $this->render('failed',['status'=>2]);
		}

		return $this->render('transfer',['model'=>$model]);
	}

	public function actionTransfertoclass()
	{
		$model = new SimpleForm;
		$model->toClass = Yii::$app->request->get('id');

		if($model->load(Yii::$app->request->post()))
		{
			$fromWho = User::findOne(['id'=>Yii::$app->user->identity->id]);
			$toClass = Banji::findOne(['id'=>$model->toClass]);
			$trade = new Trade;
			$trade->money = $model->money;
			$trade->toClass = $model->toClass;
			$trade->fromWho = $fromWho->id;
			$trade->way = 1;//用余额转账
			$trade->type = 1;//转账
			$trade->status = 1;//临时
			//$trade->transaction_id = '';
			$trade->tradeTime = date('Y-m-d H:i:s');
			if($trade->save())
			{
				$fromWho->money -= $model->money;
				$toClass->money += $model->money;
				if($fromWho->save())
				{
					if($toClass->save())
					{
						return $this->render('succeed');
					}
					$fromWho->money += $model->money;
					$fromWho->save();
					return $this->render('failed',['status'=>3]);
				}
				return $this->render('failed',['status'=>1]);
			}
			return $this->render('failed',['status'=>2]);
		}

		return $this->render('transfer',['model'=>$model]);
	}

	public function actionView()
	{
		$tradeid = Yii::$app->request->get('id');
		$user = Yii::$app->user->identity;
		$tradeinfo = Trade::findOne(['id'=>$tradeid]);
		if($tradeinfo == null)
		{
			return false;
		}
		else if(!($tradeinfo->fromWho == $user->id || $tradeinfo->toWho == $user->id) && $user->degree != 'admin')
		{
			return false;
		}
		$tradeinfo->toWho = isset($tradeinfo->toWho) ? User::findOne(['id'=>$tradeinfo->toWho])->username : null;
		$tradeinfo->fromWho = isset($tradeinfo->fromWho) ? User::findOne(['id'=>$tradeinfo->fromWho])->username : null;
		$tradeinfo->toClass = isset($tradeinfo->toClass) ? Banji::findOne(['id'=>$tradeinfo->toClass])->name : null;
		$tradeinfo->fromClass = isset($tradeinfo->fromClass) ? Banji::findOne(['id'=>$tradeinfo->fromClass])->name : null;
		return $this->render('viewtrade',['tradeinfo'=>$tradeinfo]);
	}
}

?>