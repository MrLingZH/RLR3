<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "message".
 *
 * @property int $id
 * @property int $fromWho
 * @property int $toWho
 * @property string $title
 * @property string $content
 * @property int $status 0=未读，1=已读
 * @property string $sendTime 发送时间
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fromWho', 'toWho', 'status'], 'integer'],
            [['sendTime'], 'safe'],
            [['title', 'content'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fromWho' => 'From Who',
            'toWho' => 'To Who',
            'title' => 'Title',
            'content' => 'Content',
            'status' => 'Status',
            'sendTime' => 'Send Time',
        ];
    }

    public function send()
    {
        if($this->fromWho === null || $this->toWho === null || $this->title === null || $this->content === null  || $this->status === null || $this->sendTime === null)
        {
            return false;
        }
        if($this->save())
        {
            return true;
        }
        return false;
    }
}
