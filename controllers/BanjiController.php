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
                        'pagination' => ['pageSize' => 10],
                        'key' => 'id',
                    ]);
		return $this->render('mybanji',[
			'provider'=>$provider,
		]);
	}

	public function actionMybanjidetail()
	{
		$data = Banji::findById($_GET['id']);
		if($data)
		{
			if($data->administrator != Yii::$app->user->identity->id)//如果用户不是团体的管理员
			{
				return $this->goBack();
			}
			$data->administrator = User::findIdentity($data->administrator)->username;
			$data->school = School::findById($data->school)->name;
		}
		else
		{
			return $this->goBack();
		}
		return $this->render('mybanjidetail',[
			'data'=>$data,
		]);
	}

	public function actionBanjimates()
	{
		$banji = Banji::findById($_GET['id']);
		if($banji)
		{
			if($banji->administrator != Yii::$app->user->identity->id)
			{
				return $this->goBack();
			}
			$banji->administrator = User::findIdentity($banji->administrator)->username;
			$banji->school = School::findById($banji->school)->name;
		}
		else
		{
			return $this->goBack();
		}

		//这里查询结果为
		//$mates->id 		自增长的id
		//$mates->banji 	班级id
		//$mates->mates 	成员的id
		if($mates = RelationshipBanjiMates::findAllByBanji($_GET['id']))
		{
			//将查询到的关系加工成成员信息
			foreach($mates as $key => $value)
			{
				$t_user = User::findIdentity($mates[$key]->mates);
				$mates[$key] = [];
				$mates[$key]['username'] = $t_user->username;
				$mates[$key]['email'] = $t_user->email;
			}
		}
		$provider = new \yii\data\ArrayDataProvider([
                        'allModels' => $mates,
                        'pagination' => ['pageSize' => 10],
                        'key' => 'username',
                    ]);

		return $this->render('banjimates',[
			'banji'=>$banji,
			'provider'=>$provider,
		]);
	}

}


?>