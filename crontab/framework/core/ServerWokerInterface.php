<?php
/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/8/30
 * Time: 9:56
 */

namespace framework\core;


/**
 * 服务Worker进程接口
 * @package framework\core
 */
interface ServerWokerInterface
{
    /**
     * 此事件在worker进程/task进程启动时发生。这里创建的对象可以在进程生命周期内使用。
     * swoole1.6.11之后task_worker中也会触发onWorkerStart。
     * 可以将公用的，不易变的php文件放置到onWorkerStart之前。
     * 这样虽然不能重载入代码，但所有worker是共享的，不需要额外的内存来保存这些数据。
     * onWorkerStart之后的代码每个worker都需要在内存中保存一份
     *
     * @param SWServer $server
     * @param int $workerId
     */
    public function onWorkerStart(SWServer $server, $workerId);

    /**
     * 此事件在worker进程终止时发生。在此函数中可以回收worker进程申请的各类资源。
     *
     * @param SWServer $server
     * @param int $workerId worker进程的ID
     */
    public function onWorkerStop(SWServer $server, $workerId);

    /**
     * 有新的连接进入时，在worker进程中回调。
     *
     * @param SWServer $server
     * @param int $fd 连接的文件描述符，发送数据/关闭连接时需要此参数
     */
    public function onConnect(SWServer $server, $fd);

    /**
     * TCP客户端连接关闭后，在worker进程中回调此函数。
     *
     * @param SWServer $server
     * @param int $fd 连接的文件描述符，发送数据/关闭连接时需要此参数
     */
    public function onClose(SWServer $server, $fd);

    /**
     * 接收到数据时回调此函数，发生在worker进程中。
     *
     * @param SWServer $server
     * @param int $fd TCP客户端连接的唯一标识符
     * @param int $from_id TCP连接所在的Reactor线程ID
     * @param string $data 收到的数据内容，可能是文本或者二进制内容
     */
    public function onReceive(SWServer $server, $fd, $from_id, $data);

    /**
     * 当worker进程投递的任务在task_worker中完成时，task进程会通过swoole_server->finish()方法将任务处理的结果发送给worker进程。
     * task进程的onTask事件中没有调用finish方法或者return结果，worker进程不会触发onFinish。
     * 执行onFinish逻辑的worker进程与下发task任务的worker进程是同一个进程
     *
     * @param SWServer $server
     * @param int $taskId 任务的ID
     * @param string|SWResponse $data 任务处理的结果内容
     */
    public function onFinish(SWServer $server, $taskId, $data);
}