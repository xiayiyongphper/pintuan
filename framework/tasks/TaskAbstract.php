<?php

namespace framework\tasks;

use framework\components\ToolsAbstract;

/**
 * Class TaskAbstract
 * @package framework\tasks
 */
abstract class TaskAbstract implements TaskInterface
{
    protected $logFileName;

    /**
     * @param $data
     * @param null $filename
     */
    protected function log($data, $filename = null)
    {
        ToolsAbstract::log($data, $this->getLogFileName());
    }

    protected function getLogFileName(){
        return 'task.log';
    }

    protected function getDefaultLogFileName()
    {
        $parts = explode('\\', get_called_class());
        return end($parts) . '.log';
    }

    public function start($data)
    {
        //任务开始
        $this->run($data);
    }

    /**
     * 获取最近n天的时间
     * @param $max
     * @return array
     */
    protected function getPrevDays($max)
    {
        $days = [];
        for ($i = 0; $i < $max; $i++) {
            $day = date('Y-m-d', strtotime("- $i day"));
            $days[] = $day;
        }
        return $days;
    }

    /**
     * 获取未来n天的时间
     * @param $max
     * @return array
     */
    protected function getNextDays($max)
    {
        $days = [];
        for ($i = 0; $i < $max; $i++) {
            $day = date('Y-m-d', strtotime("+ $i day"));
            $days[] = $day;
        }
        return $days;
    }
}