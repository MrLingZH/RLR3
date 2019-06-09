<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invite".
 *
 * @property int $id
 * @property int $fromWho
 * @property int $fromClass
 * @property int $toWho
 * @property string $type
 * @property int $result 处理结果：-1=拒绝，0=待处理，1=接受
 * @property string $sendTime 发送请求时间
 * @property string $dealTime 处理请求时间
 */
class Invite extends \yii\db\ActiveRecord
{
    public $email;//邀请对象的邮箱，不存数据库，查询时可对其赋值以便输出。

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invite';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fromWho', 'fromClass', 'toWho', 'result'], 'integer'],
            [['sendTime', 'dealTime'], 'safe'],
            [['type'], 'string', 'max' => 255],
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
            'fromClass' => 'From Class',
            'toWho' => 'To Who',
            'type' => 'Type',
            'result' => 'Result',
            'sendTime' => 'Send Time',
            'dealTime' => 'Deal Time',
        ];
    }
}
