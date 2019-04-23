<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;
use app\models\Message;
use app\models\MessageForm;
use app\models\RelationshipContacts;

class MessageController extends Controller
{
	public function actions()
	{
	    return [
	        'Kupload' => [
	            'class' => 'pjkui\kindeditor\KindEditorAction',
	        ]
	    ];
	}

	public function actionIndex()
	{
		if($message = Message::find()->where(['toWho'=>Yii::$app->user->identity->id])->orderBy(['sendTime'=>SORT_DESC])->all())//SORT_ASC升序，SORT_DESC降序
		{
			foreach($message as $value)
			{
				$value->toWho = User::findOne(['id'=>$value->toWho])->username;
				$value->fromWho = User::findOne(['id'=>$value->fromWho])->username;
			}
		}

		$provider = new \yii\data\ArrayDataProvider([
            'allModels' => $message,
            'pagination' => ['pageSize' => 10],
            'key' => 'id',
        ]);

		return $this->render('index',['provider'=>$provider]);
	}

	public function actionMessage()
	{
		$message = Message::findOne(['id'=>Yii::$app->request->get('id')]);
		$message->status = 1;//设置为已读
		$message->save();
		$message->fromWho = User::findOne(['id'=>$message->fromWho])->username;

		return $this->render('message',['message'=>$message]);
	}

	public function actionSet_readed()
	{
		$message = Message::findOne(['id'=>Yii::$app->request->get('id')]);
		$message->status = 1;
		$message->save();
		return $this->actionIndex();
	}

	public function actionSet_unreaded()
	{
		$message = Message::findOne(['id'=>Yii::$app->request->get('id')]);
		$message->status = 0;
		$message->save();
		return $this->actionIndex();
	}

	public function actionWrite()
	{
		$model = new MessageForm;
		$model->fromWho = Yii::$app->user->identity->username;

		if($model->load(Yii::$app->request->post()) && $model->beforSubmit())
		{
			$message = new Message;
			$message->fromWho = Yii::$app->user->identity->id;
			$message->toWho = User::findOne(['username'=>$model->toWho])->id;
			$message->title = $model->title;
			$message->content = $model->content;
			$message->status = 0;
			$message->sendTime = date("Y-m-d H:i:s");
			if($message->send())
			{
				Yii::$app->session->setFlash('Succeed');
            	return $this->refresh();
			}
			Yii::$app->session->setFlash('Failed');
            return $this->refresh();
		}

		$contacts = [];
		if($relationshipContacts = RelationshipContacts::findAll(['me'=>Yii::$app->user->identity->id]))
		{
			foreach($relationshipContacts as $key => $value)
			{
				$t_contacts = User::findOne(['id'=>$value->contacts]);
				$contacts[$key]['username'] = $t_contacts->username;
				$contacts[$key]['degree'] = $t_contacts->degree;
			}
		}

		return $this->render('write',[
			'model'=>$model,
			'contacts'=>$contacts,
		]);
	}
}

?>