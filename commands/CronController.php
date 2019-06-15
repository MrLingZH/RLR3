<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use app\models\Vote;
use app\models\Wish;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class CronController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    
    //每天执行
    public function actionCrontab_day()
    {
        $this->actionVote_clear();
        $this->actionDonate_transfer();
    }
    
    //清除逾期的投票计划
    public function actionVote_clear()
    {
        echo "Clearing vote with overdue.\n";

        $result = Vote::clearVoteWithOverdue();

        echo "Succeed: ".$result[1].".\n";
        echo "Failed: ".$result[0].".\n";

        return ExitCode::OK;
    }

    //Donate拨款
    public function actionDonate_transfer()
    {
        echo "Donate_tranfer.\n";

        $result = Wish::transferToWish();

        echo "Succeed: ".$result[1].".\n";
        echo "Failed: ".$result[0].".\n";

        return ExitCode::OK;
    }
}
