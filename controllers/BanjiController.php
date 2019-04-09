<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\BanjiForm;
use app\models\School;
use app\models\Banji;
use app\models\User;
use app\models\RelationshipBanjiMates;

class BanjiController extends Controller
{
	public function actionCreate()
	{
		$model = new BanjiForm;

		if($model->load(Yii::$app->request->post()) && $model->create())
		{
			$banji = new Banji;
			$banji->name = $model->name;
			$banji->administrator = Yii::$app->user->identity->id;
			$banji->school = Yii::$app->user->identity->school;
			$banji->money = 0;
			$banji->token = $model->getToken();
			$banji->createtime = date("Y-m-d H:i:s");
			if($banji->save())
			{
				$relationship = new RelationshipBanjiMates;
				$relationship->banji = Banji::getIdByName($model->name);
				$relationship->mates = Yii::$app->user->identity->id;
				if (!$relationship->save()) 
				{
					return $this->render('createfailed',['status'=>1]);
				}
			}
			else
			{
				return $this->render('createfailed',['status'=>2]);
			}

			return $this->render('createsucceed');
		}

		return $this->render('create',[
			'model'=>$model,
		]);
	}

	public function actionMybanji()
	{
		$mybanji = Banji::getMybanji(Yii::$app->user->identity->id);

		//如果有查询结果，则把school字段和administrator字段的id改成对应的名字
		if($mybanji)
		{
			for($i=0;$i<count($mybanji);$i++)
			{
				$mybanji[$i]->administrator = User::findIdentity($mybanji[$i]->administrator)->username;
				$mybanji[$i]->school = School::findById($mybanji[$i]->school)->name;
			}
		}

		//DataProvider数据提供者
		$provider = new \yii\data\ArrayDataProvider([
                        'allModels' => $mybanji,
                        'pagination' => ['pageSize' => 5],
                        'key' => 'id',
                    ]);
		return $this->render('mybanji',[
			'provider'=>$provider,
		]);
	}

	public function actionMybanjidetail()
	{
		$id = $_GET['id'];
		$data = Banji::findById($id);
		$data->administrator = User::findIdentity($id)->username;
		$data->school = School::findById($id)->name;
		return $this->render('mybanjidetail',[
			'data'=>$data,
		]);
	}

}


?>