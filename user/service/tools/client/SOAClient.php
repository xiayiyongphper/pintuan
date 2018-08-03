<?php

namespace service\tools\client;

use framework\components\TStringFuncFactory;
use framework\message\Message;
use message\common\Header;
use message\user\UserRequest;
use message\user\UserStore;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/8
 * Time: 12:01
 */
class SOAClient extends ClientAbstract
{
    public $responseClass = null;
    public $model = 'merchant';
    public $method = null;

    protected $_customer = 33305;
    protected $_authToken = 'yQVTy3q1cFHuc7OW';

    public function onConnect($client)
    {
        echo "[Client]: Connected to server." . PHP_EOL;
        $argv = $_SERVER['argv'];
        if (count($argv) == 2) {
            $method = $argv[1];
            if (method_exists($this, $method)) {
                $this->$method();
            } else {
                $class = new \ReflectionClass('service\models\SOAClient');
                $methods = $class->getMethods();
                echo 'Callable methods:' . PHP_EOL;
                foreach ($methods as $index => $method) {
                    echo $index . ':' . $method->getName() . PHP_EOL;
                }
                echo sprintf('Total:%s', count($methods)) . ' method(s)' . PHP_EOL;
            }
        } else {
              echo "client connected  123" . PHP_EOL;
//            $this->getUserStoreList();
//            $this->editUserStore();
            $this->getShareConfig();
        }
    }

    public function onReceive($client, $data)
    {
        $message = new Message();
        $message->unpackResponse($data);
        $responseClass = $this->responseClass;
        if ($message->getHeader()->getCode() > 0) {
            echo sprintf('程序执行异常：%s', $message->getHeader()->getMsg()) . PHP_EOL;
        } else {
            if (TStringFuncFactory::create()->strlen($message->getPackageBody()) > 0) {
                $response = new $responseClass();
                $response->parseFromString($message->getPackageBody());
                echo PHP_EOL;
                print_r($response->toArray());
            } else {
                print_r('返回值为空');
            }
        }
    }

    public function getUserStoreList(){
        $this->responseClass = 'message\user\UserResponse';
        $request = new UserRequest();
        $request->setUserId(27);

        $header = new Header();
        $header->setRoute('user.getUserStoreList');
        $data = Message::pack($header, $request);
        $this->send($data);
    }

    public function editUserStore(){
        $this->responseClass = 'message\user\UserResponse';
        $request = new UserStore();

        $request->setUserId(27);
        $request->setStoreId(1);
        $request->setName('aaaa');
        $request->setPhone('123123');

        $header = new Header();
        $header->setRoute('user.editUserStore');
        $data = Message::pack($header, $request);
        $this->send($data);
    }

    public function getShareConfig(){
        $this->responseClass = 'message\user\ShareConfigResponse';

        $header = new Header();
        $header->setRoute('user.getShareConfig');
        $data = Message::pack($header, true);
        $this->send($data);
    }

}
