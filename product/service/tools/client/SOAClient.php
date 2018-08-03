<?php

namespace service\tools\client;

use framework\components\TStringFuncFactory;
use framework\message\Message;
use message\common\Header;
use message\common\SourceEnum;
use message\product\NewUserActivityReq;
use message\product\ProductDetailReq;
use message\product\ProductListReq;
use message\product\SecondCategoryReq;
use message\product\ThirdCategoryReq;

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
            $this->newUserProductList();
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

    public function productDetail(){
        $this->responseClass = 'message\product\ProductDetailRes';
        $request = new ProductDetailReq();
        $request->setProductId(17);
        $header = new Header();
        $header->setRoute('product.productDetail');
        $data = Message::pack($header, $request);
        $this->send($data);
    }

    public function productList(){
        $this->responseClass = 'message\product\ProductListRes';
        $request = new ProductListReq();
        $request->appendWholesalerIds(19);
        $request->appendWholesalerIds(4);
        $request->setThirdCategoryId(206);
        $header = new Header();
        $header->setRoute('product.getProductList');
        $data = Message::pack($header, $request);
        $this->send($data);
    }

    public function secondCategory(){
        $this->responseClass = 'message\product\CategoryRes';
        $request = new SecondCategoryReq();
        $request->appendWholesalerIds(4);
        $header = new Header();
        $header->setRoute('product.getSecondCategory');
        $data = Message::pack($header, $request);
        $this->send($data);
    }

    public function thirdCategory(){
        $this->responseClass = 'message\product\CategoryRes';
        $request = new ThirdCategoryReq();
        $request->appendWholesalerIds(4);
        $request->setSecondCategoryId(3);
        $header = new Header();
        $header->setRoute('product.getThirdCategory');
        $data = Message::pack($header, $request);
        $this->send($data);
    }

    public function topicProductList(){
        $this->responseClass = 'message\product\ProductListRes';
        $request = new ProductListReq();
        $request->appendWholesalerIds(19);
        $request->appendWholesalerIds(4);
        $request->setTopicId(4);

        $header = new Header();
        $header->setRoute('product.topicProductList');
        $data = Message::pack($header, $request);
        $this->send($data);
    }

    public function getNewUserActivity(){
        $this->responseClass = 'message\product\NewUserActivityRes';
        $request = new NewUserActivityReq();
        $request->appendWholesalerId(11);
        $request->setCity(440300);
        $request->setStoreId(1);

        $header = new Header();
        $header->setRoute('product.getNewUserActivity');
        $data = Message::pack($header, $request);
        $this->send($data);
    }

    public function newUserProductList(){
        $this->responseClass = 'message\product\ProductListRes';
        $request = new NewUserActivityReq();
        $request->setActivityId(1);
        $request->appendWholesalerId(11);

        $header = new Header();
        $header->setRoute('product.newUserProductList');
        $data = Message::pack($header, $request);
        $this->send($data);
    }

}
