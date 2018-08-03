<?php

namespace framework\message;

use framework\components\ToolsAbstract;
use framework\components\TStringFuncFactory;
use message\common\Header;
use message\common\ResponseHeader;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/8
 * Time: 14:44
 */
class Message
{
    /**
     * 内置几种传输协议
     * zzzz:json
     * yyyy:内置已经实现了
     */
    const PROTOCOL_JSON = 'zzzz';
    const PROTOCOL_PB = '';
    /**
     * @var string
     */
    protected $_packageBody;

    protected $_rsaSide = 'server';
    /**
     * @var \message\common\Header
     */
    protected $_header;
    protected $_headerLength;
    protected $_pkgLength;
    protected $protocol;

    /**
     * 根据header中的protocol判断使用那种数据传输协议。可选为：pb，json
     * @param \message\common\Header|\message\common\ResponseHeader $header
     * @param \framework\protocolbuffers\Message|array $body
     * @param string $RSA_SIDE
     * @return string
     */
    public static function pack($header, $body, $RSA_SIDE = 'server')
    {
        $packed = '';

        try {
            if ($body instanceof \framework\protocolbuffers\Message) {
                $packed = $body->serializeToString();
            }
        } catch (\Exception $e) {
            ToolsAbstract::logError($e);
        } catch (\Error $e) {
            ToolsAbstract::logError($e);
        }

        $headerPacked = $header->serializeToString();
        $headerPacked = pack('N', TStringFuncFactory::create()->strlen($headerPacked)) . $headerPacked;
        $packed = $headerPacked . $packed;
        $packed = pack('N', TStringFuncFactory::create()->strlen($packed)) . $packed;
        return $packed;
    }

    /**
     * @param $data
     * @param $RSA_SIDE
     *
     * @return $this
     */
    public function unpack($data, $RSA_SIDE = 'server')
    {
        $pkgLength = TStringFuncFactory::create()->substr($data, 0, 4);
        $pkgLength = unpack('N', $pkgLength);
        $this->_pkgLength = $pkgLength[1];
        $headLength = TStringFuncFactory::create()->substr($data, 4, 4);
        $headLength = unpack('N', $headLength);
        $this->_headerLength = $headLength[1];
        $headerPkg = TStringFuncFactory::create()->substr($data, 8, $this->_headerLength);
        $this->_header = new Header();
        $this->_header->parseFromString($headerPkg);
        $this->_packageBody = TStringFuncFactory::create()->substr($data, 8 + $this->_headerLength);
        $this->_rsaSide = $RSA_SIDE;
        return $this;
    }

    /**
     * @param $data
     * @param $RSA_SIDE
     *
     * @return $this
     */
    public function unpackResponse($data, $RSA_SIDE = 'server')
    {
        $pkgLength = TStringFuncFactory::create()->substr($data, 0, 4);
        $pkgLength = unpack('N', $pkgLength);
        $this->_pkgLength = $pkgLength[1];
        $headLength = TStringFuncFactory::create()->substr($data, 4, 4);
        $headLength = unpack('N', $headLength);
        $this->_headerLength = $headLength[1];
        $headerPkg = TStringFuncFactory::create()->substr($data, 8, $this->_headerLength);
        $this->_header = new ResponseHeader();
        $this->_header->parseFromString($headerPkg);
        $this->_packageBody = TStringFuncFactory::create()->substr($data, 8 + $this->_headerLength);
        $this->_rsaSide = $RSA_SIDE;
        return $this;
    }

    /**
     * @return string
     */
    public function getPackageBody()
    {
        return $this->_packageBody;
    }

    /**
     * @return \service\message\common\Header|\service\message\common\ResponseHeader
     */
    public function getHeader()
    {
        return $this->_header;
    }

    /**
     * @return mixed
     */
    public function getHeaderLength()
    {
        return $this->_headerLength;
    }

    /**
     * @return mixed
     */
    public function getPkgLength()
    {
        return $this->_pkgLength;
    }

}