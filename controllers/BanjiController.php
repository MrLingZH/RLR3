<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\BanjiForm;
use app\models\School;
use app\models\Banji;
use app\models\User;
use app\models\RelationshipBanjiMates;
use app\models\Vote;
use app\models\Wish;
use app\models\Trade;
use app\models\InviteForm;
use app\models\Invite;

class BanjiController extends Controller
{
	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [
                    'create',
                    'mybanji',
                    'banjiincludeme',
                    'mybanjidetail',
                    'banjimates',
                    'tradelist',
                    'invite',
                ],
                'rules' => [
                    [
                        'actions' => ['create','mybanji','banjiincludeme','mybanjidetail','invite'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['banjimates','tradelist'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function($rule,$action)
                        {
                            $user = Yii::$app->user->identity;
                            if(!$banji = Banji::findOne(['id'=>Yii::$app->request->get('id')]))return false;
                            return $banji->isMate($user->id);
                        }
                    ],
                ],
            ],
        ];
    }

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
				$relationship->save();
			}
			else
			{
				return $this->render('createfailed',['status'=>1]);
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

	public function actionBanjiincludeme()
	{
		$include = RelationshipBanjiMates::findAll(['mates'=>Yii::$app->user->identity->id]);
		$mybanji = [];
		if($include)
		{
			foreach($include as $value)
			{
				$banji = Banji::findOne(['id'=>$value->banji]);
				array_push($mybanji, $banji);
			}
		}
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
		$data = Banji::findOne(['id'=>Yii::$app->request->get('id')]);
		if($data)
		{
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
		$banji = Banji::findById(Yii::$app->request->get('id'));
		$banji->administrator = User::findIdentity($banji->administrator)->username;
		$banji->school = School::findById($banji->school)->name;

		//这里查询结果为
		//$mates->id 		自增长的id
		//$mates->banji 	班级id
		//$mates->mates 	成员的id
		if($mates = RelationshipBanjiMates::find()->where(['banji'=>$banji->id])->orderBy(['id'=>SORT_ASC])->all())
		{
			//将查询到的关系加工成成员信息
			foreach($mates as $key => $value)
			{
				$t_user = User::findIdentity($value->mates);
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

		//获取计划中的投票活动
		$allNotCompleteVotes = Vote::findAll(['banji'=>$banji->id,'status'=>0]);
		//获取正在进行的投票活动
		$beginVote = Vote::findAll(['banji'=>$banji->id,'status'=>[1,4]]);
		//获取正在帮助的心愿
		$donateByUs = Wish::findAll(['fromClass'=>$banji->id,'status'=>[1,3]]);
		//逾期项目
		$donateByUsOverDue = Wish::findAll(['fromClass'=>$banji->id,'status'=>5]);

		return $this->render('banjimates',[
			'banji'=>$banji,
			'provider'=>$provider,
			'allNotCompleteVotes'=>$allNotCompleteVotes,
			'beginVote'=>$beginVote,
			'donateByUs'=>$donateByUs,
			'donateByUsOverDue'=>$donateByUsOverDue,
		]);
	}

	public function actionTradelist()
	{
		$banjiid = Yii::$app->request->get('id');
		$trade =Trade::find()->where(['toClass'=>$banjiid])->orWhere(['toClass'=>$banjiid])->orderBy(['tradeTime'=>SORT_DESC])->all();

		$provider = new \yii\data\ArrayDataProvider([
                        'allModels' => $trade,
                        'pagination' => ['pageSize' => 10],
                        'key' => 'id',
                    ]);

		return $this->render('tradelist',[
			'provider'=>$provider,
			'banjiid'=>$banjiid,
		]);
	}

	public function actionInvite()
	{	
		if(!$banji = Banji::findOne(['id'=>Yii::$app->request->get('id')]))return $this->redirect(['site/appcenter']);
		if(!$banji->isAdministrator(Yii::$app->user->identity->id))return $this->redirect(['site/appcenter']);
		$model = new InviteForm;
		$model->banji = $banji;

		if($model->load(Yii::$app->request->post()) && $model->validate())
		{
			if($model->send())Yii::$app->session->setFlash('succeed');
			else Yii::$app->session->setFlash('failed');
		}

		return $this->render('invite',['model'=>$model]);
	}

	public function actionInvited()
	{
		if(!$banji = Banji::findOne(['id'=>Yii::$app->request->get('id')]))return $this->redirect(['site/appcenter']);
		if(!$banji->isAdministrator(Yii::$app->user->identity->id))return $this->redirect(['site/appcenter']);

		$invitations =Invite::find()->where(['fromClass'=>$banji->id])->orderBy(['sendTime'=>SORT_DESC])->all();

		if($invitations)
		{
			foreach($invitations as $v)
			{
				$t_user = User::findOne(['id'=>$v->toWho]);
				$v->toWho = $t_user->username;
				$v->email = $t_user->email;
			}
		}

		$provider = new \yii\data\ArrayDataProvider([
                        'allModels' => $invitations,
                        'pagination' => ['pageSize' => 10],
                        'key' => 'id',
                    ]);

		return $this->render('invited',[
			'provider'=>$provider,
			'banjiid'=>$banji->id,
		]);
	}

}


?>