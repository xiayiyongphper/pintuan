<?php
namespace framework\protocolbuffers;

use framework\components\ToolsAbstract;

/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 16-12-12
 * Time: 下午12:57
 * Email: henryzxj1989@gmail.com
 */
abstract class Message extends \ProtobufMessage
{
    protected $debug = false;

    public static function __callStatic($name, $arguments)
    {
        ToolsAbstract::log(func_get_args());
    }

    /**
     * setFromWithPHP
     * @param array $params
     */
    public function setFromWithPHP(array $params)
    {
        $class = new \ReflectionClass(get_called_class());
        $this->log(__FILE__ . ':' . __LINE__);
        $this->log($class->getName() . '-start', 'setFrom.log');
        $fields = $this->fields();
        foreach ($params as $key => $value) {
            $this->log(__FILE__ . ':' . __LINE__);
            $this->log('key:' . $key, 'setFrom.log');
            $pos = $class->getConstant($key);
            $this->log(__FILE__ . ':' . __LINE__);
            $this->log('pos:' . $pos, 'setFrom.log');
            if ($pos == 0) {
                $this->log('key:' . $key, 'setFrom1.log');
                $this->log($class->getName(), 'setFrom1.log');
                $this->log($fields, 'setFrom1.log');
            }
            $type = $fields[$pos]['type'];
            $this->log(__FILE__ . ':' . __LINE__);
            $this->log('type:' . $type, 'setFrom.log');
            $repeated = false;
            if (isset($fields[$pos]['repeated']) && $fields[$pos]['repeated'] === true) {
                $repeated = true;
            }
            $this->log(__FILE__ . ':' . __LINE__);
            $this->log('repeated:' . $repeated, 'setFrom.log');
            $this->log(__FILE__ . ':' . __LINE__);
            $this->log($value, 'setFrom.log');
            if (is_int($type)) {
                if ($repeated === true) {
                    if (is_array($value)) {
                        foreach ($value as $_value) {
                            $this->log(__FILE__ . ':' . __LINE__);
                            $this->log($_value, 'setFrom.log');
                            $this->append($pos, $_value);
                        }
                    } else {
                        $this->log(__FILE__ . ':' . __LINE__);
                        $this->log($value, 'setFrom.log');
                        $this->append($pos, $value);
                    }
                } else {
                    $this->log(__FILE__ . ':' . __LINE__);
                    $this->log($value, 'setFrom.log');
                    $this->set($pos, $value);
                }
            } else {
                if ($repeated === true) {
                    if (is_array($value)) {
                        foreach ($value as $_value) {
                            /** @var Message $fieldValue */
                            $this->log(__FILE__ . ':' . __LINE__);
                            $this->log($type, 'setFrom.log');
                            $this->log($_value, 'setFrom.log');
                            $fieldValue = new $type;
                            $fieldValue->setFrom($_value);
                            $this->append($pos, $fieldValue);
                        }
                    } else {
                        /** @var Message $fieldValue */
                        $this->log(__FILE__ . ':' . __LINE__);
                        $this->log($type, 'setFrom.log');
                        $this->log($value, 'setFrom.log');
                        $fieldValue = new $type;
                        $fieldValue->setFrom($value);
                        $this->append($pos, $fieldValue);
                    }
                } else {
                    /** @var Message $fieldValue */
                    $this->log(__FILE__ . ':' . __LINE__);
                    $this->log($type, 'setFrom.log');
                    $this->log($value, 'setFrom.log');
                    $fieldValue = new $type;
                    $fieldValue->setFrom($value);
                    $this->set($pos, $fieldValue);
                }
            }
        }
        $this->log(__FILE__ . ':' . __LINE__);
        $this->log($class->getName() . '-end', 'setFrom.log');
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @param bool $onlySet
     * @return array
     */
    public function toArray($onlySet = true)
    {
        $arr = [];
        foreach ($this->fields() as $pos => $field) {
            $_field = $this->get($pos);
            if (is_array($_field)) {
                foreach ($_field as $__field) {
                    if ($__field instanceof \framework\protocolbuffers\Message) {
                        $arr[$field['name']][] = $__field->toArray($onlySet);
                    } else {
                        $arr[$field['name']][] = $__field;
                    }
                }
            } else if ($_field instanceof \framework\protocolbuffers\Message) {
                $arr[$field['name']] = $_field->toArray($onlySet);
            } else {
                if (isset($_field) || (!isset($_field) && $onlySet === false)) {
                    $arr[$field['name']] = $_field;
                } else {
                    //ignore unset value
                    ToolsAbstract::log('ignore unset value', 'pb.log');
                }
            }
        }
        return $arr;
    }

    public abstract function fields();

    protected function log($msg, $filename = 'message.log')
    {
        if ($this->debug) {
            ToolsAbstract::log($msg, $filename);
        }
    }


    public function getValues()
    {
        return $this->values;
    }
}