<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/8
 * Time: 17:06
 */

namespace framework;


use framework\components\es\Timeline;
use yii\base\ErrorException;

class HttpServer extends Application
{

    public $call_back_uri = [
        'pay/wxnotify',
    ];

    public function __construct($config = [])
    {
        parent::__construct($config);
        $serverConfig = \Yii::$app->params['http_server_config'];
        if (!is_array($serverConfig)) {
            throw new \Exception('invalid ip port config');
        }
        $this->serverConfig = $serverConfig;
        $this->log(\Yii::$app->params);

        $ipPort = \Yii::$app->params['ip_port'];
        if (!is_array($ipPort)) {
            throw new \Exception('ip port config not found');
        }
        if (!isset($ipPort['http_port'])) {
            throw new \Exception('invalid ip port config');
        }

        $this->host = $ipPort['host'];
        $this->port = $ipPort['http_port'];

    }

    public function onRequest(\swoole_http_request $request, \swoole_http_response $response)
    {
        $route = $request->server['request_uri'];
        $route = ltrim($route,'/');
        $params = $request->rawContent();
        Tool::log($params,str_replace("/","-",$route).".log");

        $return  = '';
        $callBackUriFlag = false;
        if(in_array($route, $this->call_back_uri)){
            $callBackUriFlag = true;
        }else{
            $response->header('Content-Type', 'application/json');
            $return = [
                'code' => 0,
                'msg' => '',
                'data' => []
            ];
        }

        try{
            $class = $this->getResource($route);
            $method = 'run';
            /** @var  \framework\ApiAbstract $model */
            $model = new $class();

            if(!$callBackUriFlag){
                $params = (array)json_decode($params, true);
            }
            $start = microtime(true);
            $result = $model->$method($params);
            $end = microtime(true);
            Tool::log($end - $start, str_replace("\\","-",$class) . '.log');

            if($callBackUriFlag){
                $return = $result;
            }else{
                $return['data'] = $result;
                Tool::log($return, 'request.log');
//                $return = json_encode($return);
            }

            // 上报接口时间到接口监控系统
            $elapsed = $end - $start;
            $routeEs = str_replace('/','.',$route);
            Timeline::get()->report($routeEs, str_replace("\\","-",$class), ENV_SYS_NAME, $elapsed, $return['code']);

        }catch (ErrorException $e) {
            Tool::logException($e);
            if(!$callBackUriFlag){
                $return = [
                    'code' => $e->getCode(),
                    'msg' => '系统繁忙，请稍后重试！'
                ];
            }
        } catch (\Exception $e) {
            Tool::logException($e);
            if(!$callBackUriFlag){
                $return = [
                    'code' => $e->getCode(),
                    'msg' => $e->getMessage()
                ];
            }
        } catch (\Error $e) {
            Tool::logError($e);
            if(!$callBackUriFlag){
                $return = [
                    'code' => $e->getCode(),
                    'msg' => $e->getMessage()
                ];
            }
        }

        if(is_array($return)){
            $return = json_encode($return);
        }
        $response->end($return);



//
//        if (in_array($route, $this->call_back_uri)) {
//            try {
//                $start = microtime(true);
//                $return = $model->$method($params);
//                $response->end($return);
//                $end = microtime(true);
//
//                Tool::log($end - $start, str_replace("\\","-",$class) . '.log');
//            } catch (\Exception $e) {
//                Tool::logException($e);
//            } catch (\Error $e) {
//                Tool::logError($e);
//            }
//
//        } else {
//            $response->header('Content-Type', 'application/json');
//            $return = [
//                'code' => 0,
//                'msg' => '',
//                'data' => ''
//            ];
//
//            try {
//                $start = microtime(true);
//                $return['data'] = $model->$method($params);
//                $end = microtime(true);
//                Tool::log($end - $start, str_replace("\\","-",$class) . '.log');
//            } catch (ErrorException $e) {
//                $return['code'] = $e->getCode();
//                $return['msg'] = '系统繁忙，请稍后重试！';
//                Tool::logException($e);
//            } catch (\Exception $e) {
//                $return['code'] = $e->getCode();
//                $return['msg'] = $e->getMessage();
//                Tool::logException($e);
//            } catch (\Error $e) {
//                $return['code'] = $e->getCode();
//                $return['msg'] = $e->getMessage();
//                Tool::logError($e);
//            }
//            Tool::log($return, 'request.log');
//            $response->end(json_encode($return));
//        }
    }

    public function onTask(\swoole_server $server, $task_id, $from_id, $taskData)
    {
        Tool::log($taskData, 'task.log');
    }

    public function serve()
    {
        if (empty($this->host) || empty($this->port) || empty($this->serverConfig)) {
            throw new \Exception('invalid config');
        }

        $server = new \swoole_http_server($this->host, $this->port);
        $server->set($this->serverConfig);
        $server->addlistener("127.0.0.1", $this->port,SWOOLE_SOCK_TCP);

        $server->on('request', [$this, 'onRequest']);
        $server->on('close', [$this, 'onClose']);
        $server->on('task', [$this, 'onTask']);
        $server->on('finish', [$this, 'onFinish']);
        $server->on('start', [$this, 'onStart']);
        $server->on('workerstart', [$this, 'onWorkerStart']);
        $server->on('workerstop', [$this, 'onWorkerStop']);
        $server->on('shutdown', [$this, 'onShutdown']);
        $server->on('WorkerError', [$this, 'onWorkerError']);
        $server->on('ManagerStart', [$this, 'onManagerStart']);
        $server->on('ManagerStop', [$this, 'onManagerStop']);

        $server->start();
    }
}