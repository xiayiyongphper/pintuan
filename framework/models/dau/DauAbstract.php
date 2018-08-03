<?php
/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 17-7-26
 * Time: 下午3:53
 */

namespace framework\models\dau;

use framework\components\ToolsAbstract;

abstract class DauAbstract implements DauInterface
{
    private $id = 0;

    /**
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function add()
    {
        return ToolsAbstract::getRedis()->setBit($this->getKey(), $this->getId(), 1);
    }

    public function count()
    {
        return ToolsAbstract::getRedis()->bitCount($this->getKey());
    }

    /**
     * @param string $key
     * @return array
     * 从redis拿出字符串，转换成二进制进行遍历
     * 遍历时12个字符为一组，提高效率
     */
    public function getAllIds($key = null)
    {
        $redis = ToolsAbstract::getRedis();
        if (!$key) {
            $key = $this->getKey();
        }
        ToolsAbstract::log('customer_count:' . $redis->bitCount($key), 'wangyang.log');
        $hexString = $redis->get($key);//128w,320k
        $bytes = unpack("H*", $hexString);
        $bytes = $bytes[1];
        $length = strlen($bytes);
        $byteCount = 0;
        $offset = 0;
        $bulk = 12;
        $ids = [];
        while ($offset < $length) {
            $str = substr($bytes, $offset, $bulk);
            if (strlen($str) !== $bulk) {
                $bulk = strlen($str);
            }
            $dec = hexdec($str);
            $offset += $bulk;
            $size = $bulk * 4;
            if ($dec > 0) {
                $bin = decbin($dec);
                $bin = str_pad($bin, $size, '0', STR_PAD_LEFT);
//            echo $bin . PHP_EOL;
                $pos = 0;
                while ($pos < $size) {
                    $pos = strpos($bin, '1', $pos);
                    if ($pos === false) {
                        break;
                    } else {
                        $ids[] = $byteCount + $pos++;
                    }
                }
            }
            $byteCount += $size;
        }
        return $ids;
    }
}