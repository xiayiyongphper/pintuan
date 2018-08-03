<?php

namespace framework\tasks;
/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 16-7-26
 * Time: 下午2:29
 * Email: henryzxj1989@gmail.com
 */
interface TaskInterface
{
    /**
     * 仅当客返回值为\framework\protocolbuffers\Message类型时，消息才能传递到客户端
     * @param string $bytes
     * @return mixed
     */
    public function run($bytes);

}