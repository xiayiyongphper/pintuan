<?php

/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/9/5
 * Time: 15:57
 */
namespace common\helper;

use common\models\Crontab;
use common\models\CrontabHistory;

/**
 * Class MessageHelper
 * @package common\helpers
 */
class MessageHelper
{
    const FROM_WORKER = CrontabHistory::FROM_WORKER;
    const FROM_RPC_INTERNAL = CrontabHistory::FROM_RPC_INTERNAL;
    const FROM_RPC_REMOTE = CrontabHistory::FROM_RPC_REMOTE;
    const FROM_CLI = CrontabHistory::FROM_CLI;

    /**
     * @param Crontab $job
     * @param string|int $from
     * @param array $data
     * @return string
     */
    public static function packJob(Crontab $job, $from = self::FROM_WORKER, array $data = [])
    {
        $data = array_merge(
            $data,
            [
                '@from' => $from,
                '@task_id' => $job->entity_id,
                '@scheduled_timestamp' => $job->scheduledTimestamp,
                '@timestamp' => time(),
                '@params' => $job->params,
            ]
        );
        return $data;
    }
}