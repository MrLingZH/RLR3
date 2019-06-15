<?php

namespace app\models;

use Yii;
use app\models\Wish;

/**
 * This is the model class for table "vote".
 *
 * @property int $id
 * @property int $banji 发起投票的班级
 * @property string $needers 被资助的人
 * @property string $title 主题
 * @property int $Nmax 计划资助人数
 * @property int $status 状态，0=计划中，1=投票进行中，2=投票结束，3=已删除，4=等待重新投票
 * @property int $isReset 记录是否重新发起投票
 * @property string $launchTime 发起时间
 * @property string $endTime 结束时间
 * @property string $result 投票结果
 * @property string $haveVoted 已投记录
 */
class Vote extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vote';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['banji', 'status', 'isReset'], 'integer'],
            [['launchTime', 'endTime'], 'safe'],
            [['needers'], 'string', 'max' => 1000],
            [['result'], 'string', 'max' => 3000],
            [['haveVoted'], 'string', 'max' => 10000],
            [['title'], 'string', 'max' => 255],

            [['title','Nmax','endTime'], 'required'],
            ['Nmax','integer','max' => 10],
            ['endTime','validateEndTime'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'banji' => 'Banji',
            'needers' => 'Needers',
            'title' => '投票主题',
            'Nmax' => '计划捐助人数',
            'status' => 'Status',
            'isReset' => 'Is Reset',
            'launchTime' => 'Launch Time',
            'endTime' => '投票结束时间',
            'result' => '投票结果',
            'haveVoted' => '已投记录',
        ];
    }

    const ONE_DAY_IN_SECONDS = 86400;

    public function validateEndTime($attribute, $params)
    {
        if(strtotime($this->endTime)<strtotime(date('Y-m-d'))+self::ONE_DAY_IN_SECONDS)
        {
            $this->addError($attribute,'时间选择必须在24h之后');
        }
    }

    public function beforeSubmit()
    {
        if($this->validate())
        {
            return true;
        }
        return false;
    }

    public function init()
    {
        $this->haveVoted = json_decode($this->haveVoted,1);
        $this->result = json_decode($this->result,1);
    }

    public function beforSave()
    {
        $this->haveVoted = json_encode($this->haveVoted);
        $this->result = json_encode($this->result);
    }

    //获取用户已投票数(把每个投票对象的该用户投的票的数量加起来)
    public function getVotesFromUser($uid)
    {
        $votes = 0;
        if($this->haveVoted != null)
        {
            foreach($this->haveVoted as $v1)//遍历每个一投票对象wish，$v1为[wishid=>[投票者1,投票者2,...]]的格式
            {
                foreach($v1 as $v2)//遍历每个投票对象的投票者
                {
                    if($uid == $v2)
                    {
                        $votes += 1;//已投票数+1
                        break;
                    }
                }
            }
        }
        return $votes;
    }

    //判断用户是否已投某该对象
    public function isVoteInWish($uid,$wishid)
    {
        if($this->haveVoted != null)
        {
            if(!isset($this->haveVoted[$wishid]))
            {
                return false;
            }
            foreach($this->haveVoted[$wishid] as $v)
            {
                if($uid == $v)
                {
                    return true;
                }
            }
        }
        return false;
    }

    //删除投票计划
    public function deleteVote()
    {
        if($this->needers != null)
        {
            //将已选择计划投票的对象释放回愿望池
            $needers = explode(',',$this->needers);
            foreach($needers as $v)
            {
                $t_wish = Wish::findOne(['id'=>$v]);
                $t_wish->status = 0;
                $t_wish->save();
            }
        }
        //$this->status = 3;
        $this->delete();
    }

    //清除逾期的投票计划
    public static function clearVoteWithOverdue()
    {
        $vote = self::findAll(['status'=>0]);
        $result = [0=>0,1=>0];
        foreach($vote as $v)
        {
            $date = date('Y-m-d H:i:s');
            if(strtotime($date) > strtotime($v->endTime))
            {
                $v->needers = null;
                $v->deleteVote();
                $result[1] += 1;
            }
        }
        return $result;
    }
}
