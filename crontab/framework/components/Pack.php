<?php

namespace framework\components;


class Pack
{

    /**
     * @param $object
     * @return string
     */
    public static function pack($object)
    {
        $object = serialize($object);
        $data = pack('N', TStringFuncFactory::create()->strlen($object)) . $object;
        return $data;
    }

    /**
     *
     * @param $data
     * @return object
     */
    public static function unpack($data)
    {
        $object = TStringFuncFactory::create()->substr($data, 4);
        $object = unserialize($object);
        return $object;
    }

}