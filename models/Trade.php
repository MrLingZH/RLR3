<?php

namespace app\models;

use Yii;
use app\models\User;
use app\models\Banji;

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

    public function transferToPerson($fromWho,$toWho,$money)
    {
        $fromWho = User::findOne(['id'=>$fromWho]);
        if($fromWho->money < $money || $money < 1)return false;
        $toWho = User::findOne(['id'=>$toWho]);
        $this->money = $money;
        $this->toWho = $toWho->id;
        $this->fromWho = $fromWho->id;
        $this->way = 1;//用余额转账
        $this->type = 1;//转账
        $this->status = 1;
        //$this->transaction_id = '';
        $this->tradeTime = date('Y-m-d H:i:s');
        $this->save();
        $fromWho->money -= $money;
        $fromWho->save();
        $toWho->money += $money;
        $toWho->save();
        return true;
    }

    public function transferToClass($fromWho,$toClass,$money)
    {
        $fromWho = User::findOne(['id'=>$fromWho]);
        if($fromWho->money < $money || $money < 1)return false;
        $toClass = Banji::findOne(['id'=>$toClass]);
        $this->money = $money;
        $this->toClass = $toClass->id;
        $this->fromWho = $fromWho->id;
        $this->way = 1;//用余额转账
        $this->type = 1;//转账
        $this->status = 1;
        //$this->transaction_id = '';
        $this->tradeTime = date('Y-m-d H:i:s');
        $this->save();
        $fromWho->money -= $money;
        $fromWho->save();
        $toClass->money += $money;
        $toClass->save();
        return true;
    }

    public function transfer_ClassToPerson($fromClass,$toWho,$money)
    {
        $fromClass = Banji::findOne(['id'=>$fromClass]);
        if($fromClass->money < $money || $money < 1)return false;
        $toWho = User::findOne(['id'=>$toWho]);
        $this->money = $money;
        $this->toWho = $toWho->id;
        $this->fromClass = $fromClass->id;
        $this->way = 1;//用余额转账
        $this->type = 1;//转账
        $this->status = 1;
        //$this->transaction_id = '';
        $this->tradeTime = date('Y-m-d H:i:s');
        $this->save();
        $fromClass->money -= $money;
        $fromClass->save();
        $toWho->money += $money;
        $toWho->save();
        return true;
    }
}
