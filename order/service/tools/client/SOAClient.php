<?php

namespace service\tools\client;

use framework\components\TStringFuncFactory;
use framework\message\Message;
use service\message\common\Header;
use service\message\common\SourceEnum;
use service\message\merchant\purchaseHistoryRequestV32;

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
            $this->purchaseHistoryV32();
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

    public function purchaseHistoryV32(){
        $this->responseClass = 'service\message\merchant\purchaseHistoryResponseV32';
        $request = new purchaseHistoryRequestV32();
        $request->setWholesalerId(33);
        $request->setCustomerId($this->_customer);
        $request->setAuthToken($this->_authToken);
//        $request->setBrand('银鹭');
        $request->setSort(1);
        $request->setWholesalerId(33);
        $header = new Header();
        $header->setSource(SourceEnum::MERCHANT);
        $header->setVersion(1);
        $header->setRoute('merchant.purchaseHistoryV32');
        $data = Message::pack($header, $request);
        $this->send($data);
    }

}
