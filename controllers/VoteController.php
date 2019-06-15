<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\User;
use app\models\Banji;
use app\models\Vote;
use app\models\Wish;
use app\models\School;
use app\models\RelationshipBanjiMates;
use yii\data\ArrayDataProvider;

class VoteController extends Controller
{
	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [
                    'launchvote',
                    'editneeders',
                    'addinvote',
                    'checked_cancel',
                    'delete',
                    'beginvote',
                    'view',
                    'vote',
                    'endvote',
                ],
                'rules' => [
                    [
                        'actions' => ['launchvote','editneeders','addinvote','checked_cancel','delete','beginvote','view','vote','endvote'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

	public function actionLaunchvote()
	{
		if(!$banji = Banji::findOne(['id'=>Yii::$app->request->get('id')]))return $this->redirect(['site/appcenter']);
		$user = Yii::$app->user->identity;
		if(!$banji->isAdministrator($user->id))return $this->redirect(['site/appcenter']);

		$vote = new Vote;
		if($vote->load(Yii::$app->request->post()) && $vote->beforeSubmit())
		{
			$vote->banji = $banji->id;
			$vote->launchTime = date('Y-m-d H:i:s');
			if(!$vote->save())
			{
				return $this->render('error',['message'=>'状态：1']);
			}
			return $this->redirect(['banji/banjimates','id'=>$banji->id]);
		}

		return $this->render('setvote',['vote'=>$vote,'id_banji'=>$banji->id]);
	}

	public function actionEditneeders()
	{
		if(!$vote = Vote::findOne(['id'=>Yii::$app->request->get('id')]))return $this->redirect(['site/appcenter']);
		$banji = banji::findOne(['id'=>$vote->banji]);
		$user = Yii::$app->user->identity;
		if(!$banji->isAdministrator($user->id))return $this->redirect(['site/appcenter']);
		
		//获取该团体的成员
		$t_menbers = RelationshipBanjiMates::findAll(['banji'=>$banji->id]);
		$menbers = [];
		foreach($t_menbers as $value)
		{
			array_push($menbers,$value->mates);
		}

		$wish = Wish::find()->where(['school'=>$banji->school,'result'=>1,'status'=>0])->andWhere(['not',['toWho'=>$menbers]])->orderBy(['wishtime'=>SORT_DESC])->all();
		foreach($wish as $v)
		{
			$v->toWho = User::findOne(['id'=>$v->toWho])->username;
			$v->school = School::findOne(['id'=>$v->school])->name;
		}

		$provider = new ArrayDataProvider([
            'allModels' => $wish,
            'pagination' => ['pageSize' => 10],
            'key' => 'id',
        ]);

		//获取已选择投票的心愿
		$wish_checked = [];
		$count_vote_checked = 0;
		if($vote->needers != null)
		{
			foreach(explode(',',$vote->needers) as $v)
			{
				$t_wish_checked = Wish::findOne(['id'=>$v]);
				$t_wish_checked2 = [];
				$t_wish_checked2['id'] = $v;
				$t_wish_checked2['toWho'] = User::findOne(['id'=>$t_wish_checked->toWho])->username;
				array_push($wish_checked,$t_wish_checked2);
				$count_vote_checked += 1;
			}
		}
      
		return $this->render('neederslist',[
			'provider'=>$provider,
			'voteid'=>$vote->id,
			'count_vote_checked'=>$count_vote_checked,
			'voteNmax'=>$vote->Nmax,
			'id_banji'=>$banji->id,
			'wish_checked'=>$wish_checked,
			//'url_back'=>Yii::$app->request->Referrer,
		]);
	}

	public function actionAddinvote()
	{
		$wishid = Yii::$app->request->get('wishid');
		$voteid = Yii::$app->request->get('voteid');

		if(!$wish = Wish::findOne(['id'=>$wishid]))return $this->redirect(['site/appcenter']);
		if(!$vote = Vote::findOne(['id'=>$voteid]))return $this->redirect(['site/appcenter']);
		$banji = banji::findOne(['id'=>$vote->banji]);
		$user = Yii::$app->user->identity;
		if(!$banji->isAdministrator($user->id))return $this->redirect(['site/appcenter']);
		if(!$wish->canDonate($user->school))return $this->redirect(['site/appcenter']);
		if($wish->status != 0)return $this->render('error',['message'=>'该心愿的状态在执行此操作之前发生了变化。']);
		
		if($vote->needers == null)
		{
			$needers = [];
			array_push($needers,$wish->id);
			$vote->needers = implode(",",$needers);//implode(',',$needers)，把数组$needers转成字符串，值用字符','隔开。
			if(!$vote->save()){return $this->render('error',['message'=>'状态：1']);}
		}
		else
		{
			$needers = explode(',',$vote->needers);//explode(',', $vote->needers)，把字符串$vote->needers以字符','为分隔符号分割成数组。
			if(count($needers)>=$vote->Nmax+1){return $this->render('error',['message'=>'已选择的投票对象已达上限']);}
			array_push($needers,$wish->id);
			$vote->needers = implode(",",$needers);
			if(!$vote->save()){return $this->render('error',['message'=>'状态：1']);}
		}
		$wish->fromClass = $vote->banji;
		$wish->fromWho = null;
		$wish->status = 2;
		if(!$wish->save()){return $this->render('error',['message'=>'状态：2']);}

		return $this->redirect(['vote/editneeders','id'=>$vote->id]);
	}

	public function actionChecked_cancel()//取消选择
	{
		$voteid = Yii::$app->request->get('voteid');
		$wishid = Yii::$app->request->get('wishid');

		if(!$vote = Vote::findOne(['id'=>$voteid]))return $this->redirect(['site/appcenter']);
		if(!$wish = Wish::findOne(['id'=>$wishid]))return $this->redirect(['site/appcenter']);
		$banji = banji::findOne(['id'=>$vote->banji]);
		$user = Yii::$app->user->identity;
		if(!$banji->isAdministrator($user->id))return $this->redirect(['site/appcenter']);
		if(!$wish->canDonate($user->school))return $this->redirect(['site/appcenter']);

		$wish->status = 0;
		$wish->fromClass = null;
		$wish->save();

		$needers = explode(',',$vote->needers);
		//array_diff($needers,[$wishid]);
		foreach($needers as $k => $v)
		{
			if($v == $wishid)
			{
				unset($needers[$k]);
				break;
			}
		}
		$vote->needers = implode(',',$needers);
		if(!$vote->save())
		{
			$wish->status = 2;
			$wish->save();
			return $this->render('error',['message'=>'状态：2']);
		}
		
		return $this->redirect(['vote/editneeders','id'=>$vote->id]);
	}

	public function actionDelete()
	{
		$voteid = Yii::$app->request->get('id');

		if(!$vote = Vote::findOne(['id'=>$voteid]))return $this->redirect(['site/appcenter']);
		$banji = banji::findOne(['id'=>$vote->banji]);
		$user = Yii::$app->user->identity;
		if(!$banji->isAdministrator($user->id))return $this->redirect(['site/appcenter']);

		$vote->deleteVote();
		
		return $this->redirect(['banji/banjimates','id'=>$banji->id]);
	}

	public function actionBeginvote()
	{
		$voteid = Yii::$app->request->get('id');

		if(!$vote = Vote::findOne(['id'=>$voteid]))return $this->redirect(['site/appcenter']);
		$banji = banji::findOne(['id'=>$vote->banji]);
		$user = Yii::$app->user->identity;
		if(!$banji->isAdministrator($user->id))return $this->redirect(['site/appcenter']);
		if(date('Y-m-d H:i:s') > $vote->endTime)return $this->render('error',['message'=>'投票时间已结束。']);

		$needers = explode(',',$vote->needers);
		$count_needers = count($needers);
		if($vote->needers == null)
		{
			return $this->render('error',['message'=>'该活动选择的资助对象数未达到计划数，无法开始进行投票。']);
		}
		//如果社区中已无其他心愿可供资助且，则跳过投票，询问其余成员意见，意见通过时直接资助。
		else if($count_needers == 1 && Wish::findOne(['school'=>$banji->school,'result'=>1,'status'=>0]) == null)
		{

		}
		else if($count_needers < $vote->Nmax + 1)
		{
			return $this->render('error',['message'=>'该活动选择的资助对象数未达到计划数，无法开始进行投票。']);
		}
		else
		{
			$vote->status = 1;
			//初始化所有票数为0
			$needers = explode(',',$vote->needers);
			$result = [];
			foreach($needers as $v)
			{
				$result[$v] = 0;
			}
			$vote->result = json_encode($result);
			$vote->haveVoted = null;
			$vote->status = 1;
			if(!$vote->save()){return $this->render('error',['message'=>'状态：1']);}
		}
		return $this->redirect(['banji/banjimates','id'=>$banji->id]);
	}

	public function actionView()
	{
		$voteid = Yii::$app->request->get('id');
		if(!$vote = Vote::findOne(['id'=>$voteid]))return $this->redirect(['site/appcenter']);

		//判断访问用户是否为该班级的成员
		$user = Yii::$app->user->identity;
		$banji = Banji::findOne(['id'=>$vote->banji]);
		if(!$banji->isMate($user->id))return $this->redirect(['site/appcenter']);
		if($vote->status != 1){return $this->render('error',['message'=>'投票已结束。']);}

		$vote->init();

		$wish = Wish::findAll(['id'=>explode(',',$vote->needers)]);

		$totalVotes = 0;//总票数
		foreach($vote->result as $v){$totalVotes += $v;}

		return $this->render('view',[
			'vote'=>$vote,
			'wish'=>$wish,
			'count'=>$vote->getVotesFromUser($user->id),//获取已投票数
			'totalVotes'=>$totalVotes,
			'id_banji'=>$banji->id,
		]);
	}

	public function actionVote()
	{
		$voteid = Yii::$app->request->get('voteid');
		$wishid = Yii::$app->request->get('wishid');

		if(!$vote = Vote::findOne(['id'=>$voteid]))return $this->redirect(['site/appcenter']);
		$vote->init();
		if(!$wish = Wish::findOne(['id'=>$wishid]))return $this->redirect(['site/appcenter']);
		if(date('Y-m-d H:i:s') > $vote->endTime){return $this->render('error',['message'=>'投票时间已截止。']);}
		if($vote->status != 1){return $this->render('error',['message'=>'投票已结束。']);}
		$user = Yii::$app->user->identity;
		$banji = Banji::findOne(['id'=>$vote->banji]);
		if(!$banji->isMate($user->id))return $this->redirect(['site/appcenter']);
		if($vote->isVoteInWish($user->id,$wishid)){return $this->render('error',['message'=>'请勿重复投票。']);}
		if($vote->getVotesFromUser($user->id)>=$vote->Nmax){return $this->render('error',['message'=>'已投完所有票。']);}

		if($vote->haveVoted == null)
		{
			$haveVoted = [];
			$haveVoted[$wishid] = [];
			array_push($haveVoted[$wishid],$user->id);
			$vote->haveVoted = json_encode($haveVoted);
		}
		else
		{
			$haveVoted = $vote->haveVoted;
			$haveVoted[$wishid] = isset($haveVoted[$wishid])?$haveVoted[$wishid]:[];
			array_push($haveVoted[$wishid],$user->id);
			$vote->haveVoted = json_encode($haveVoted);
		}

		//这里如不过不用临时中间变量$t而直接
		//$vote->result[$wishid] += 1;
		//会报错
		$t = $vote->result;
		$t[$wishid] += 1;
		$vote->result = $t;
		$vote->result = json_encode($vote->result);
		$vote->save();

		return $this->redirect(['vote/view','id'=>$vote->id]);
	}

	public function actionEndvote()
	{
		$voteid = Yii::$app->request->get('id');
		if(!$vote = Vote::findOne(['id'=>$voteid]))return $this->redirect(['site/appcenter']);
		$banji = banji::findOne(['id'=>$vote->banji]);
		$user = Yii::$app->user->identity;
		if(!$banji->isAdministrator($user->id) && $vote->status == 1)return $this->redirect(['site/appcenter']);

		$vote->init();
		//计算投票结果排名
		$result = $vote->result;
		asort($result);//升序
		//先判断是否出现票数倒数的两个并列
		$times = 0;//遍历次数
		$votes = [];//票数
		foreach($result as $v)
		{
			array_push($votes,$v);
			$times += 1;
			if($times == 2)break;
		}
		arsort($result);//降序
		if($votes[0] == $votes[1])
		{
			if($vote->status != 2 || $vote->status != 4)
			{
				$vote->status = 4;
				$vote->beforSave();
				$vote->save();
			}
			$status = 1;
		}
		else
		{
			if($vote->status != 2 || $vote->status != 4)
			{
				$times = 0;//遍历次数
				$totalTimes = count($result);//需要遍历的次数
				foreach($result as $k => $v)
				{
					$times += 1;
					$wish = Wish::findOne(['id'=>$k]);
					if($times == $totalTimes)
					{
						$wish->status = 0;
					}
					else
					{
						$wish->status = 1;
						$wish->fromWho = null;
						$wish->fromClass = $banji->id;
					}
					$wish->save();
				}
				$vote->status = 2;
				$vote->beforSave();
				$vote->save();
			}
			$status = 2;
		}

		return $this->render('result',['result'=>$result,'status'=>$status]);
	}
}


?>