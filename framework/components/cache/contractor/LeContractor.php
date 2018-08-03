<?php
/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 17-7-21
 * Time: 下午2:14
 */

namespace framework\components\cache\contractor;


use framework\components\cache\CacheAbstract;
use framework\components\cache\CacheObject;

class LeContractor extends CacheAbstract
{
    const CACHE_KEY_PREFIX = 'contractor-';

    /**
     * @param integer $id 业务员ID
     * @return CacheObject|bool
     */
    public function get($id)
    {
        $json = $this->getCache()->get($this->getKey($id));
        if ($json === false) {
            //单个，缓存找不到，通知模块进行更新
            return false;
        }
        $data = json_decode($json, true);
        $obj = new CacheObject();
        $obj->addData($data);
        return $obj;
    }

    public function mGet(array $array)
    {
        print_r($this->getKey($array));
        $data = $this->getCache()->mget($this->getKey($array));
        if ($data === false) {
            //批量，缓存找不到，通知模块进行更新
            return false;
        }
        foreach ($data as $key => $json) {
            $arr = json_decode($json, true);
            $obj = new CacheObject();
            $obj->addData($arr);
            $data[$key] = $obj;
        }
        return $data;
    }

    public function rebuild()
    {
        // TODO: Implement rebuild() method.
    }

    protected function getKey($id)
    {
        if (is_array($id)) {
            foreach ($id as $key => $_id) {
                $id[$key] = self::CACHE_KEY_PREFIX . $_id;
            }
            return $id;
        }

        //单个业务员
        if (is_integer($id)) {
            return self::CACHE_KEY_PREFIX . $id;
        }

        return $id;
    }
}