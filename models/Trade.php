<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "trade".
 *
 * @property int $id
 * @property int $money 金额
 * @property int $type 交易类型，1=转账，2=充值，3=提现
 * @property int $way 交易手段，1=余额，2=支付宝，3=微信支付
 * @property int $fromWho
 * @property int $toWho
 * @property int $fromClass
 * @property int $toClass
 * @property int $status 交易状态，-1=失败，0=处理中，1=成功
 * @property string $transaction_id 流水号
 * @property string $tradeTime
 */
class Trade extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trade';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['money', 'type', 'way', 'fromWho', 'toWho', 'fromClass', 'toClass', 'status'], 'integer'],
            [['tradeTime'], 'safe'],
            [['transaction_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'money' => 'Money',
            'type' => 'Type',
            'way' => 'Way',
            'fromWho' => 'From Who',
            'toWho' => 'To Who',
            'fromClass' => 'From Class',
            'toClass' => 'To Class',
            'status' => 'Status',
            'transaction_id' => 'Transaction_id',
            'tradeTime' => 'Trade Time',
        ];
    }
}
