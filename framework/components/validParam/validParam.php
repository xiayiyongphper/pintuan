<?php
/**
 * Created by merchant.
 * User: Ryan Hong
 * Date: 2018/3/19
 * Time: 11:04
 */

namespace framework\components\validParam;
use service\resources\Exception;

/**
 * Class validParam
 */
class validParam
{
    const CHECK_TYPE_REQUIRE = 'require';//必填
    const CHECK_TYPE_OPTIONAL = 'optional';//非必填
    const CHECK_TYPE_REPEATED = 'repeated';//数组，可为空
    const CHECK_TYPE_REPEATED_REQUIRE = 'repeated_require';//数组，且不能为空数组

    const VALUE_TYPE_STRING = 'string';
    const VALUE_TYPE_INT = 'int';
    const VALUE_TYPE_FLOAT = 'float';

    private $_checkType = [self::CHECK_TYPE_REQUIRE,self::CHECK_TYPE_OPTIONAL,self::CHECK_TYPE_REPEATED,self::CHECK_TYPE_REPEATED_REQUIRE];
//    private $_valueType = [self::VALUE_TYPE_STRING,self::VALUE_TYPE_INT,self::VALUE_TYPE_FLOAT];
    private $_data = [];
    private $_formatData = [];
    private $_rules = [];
    private $_components = [];


    /**
     * validParam constructor.
     * @param array $data 请求参数
     * @param array $rules 验证规则 示例：
     * $rules = [
            ['customer_id',validParam::CHECK_TYPE_REQUIRE,validParam::VALUE_TYPE_INT],
            ['auth_token',validParam::CHECK_TYPE_REQUIRE,validParam::VALUE_TYPE_STRING],
            ['wholesaler_id',validParam::CHECK_TYPE_REQUIRE,validParam::VALUE_TYPE_INT],
        ];
     * @throws \Exception
     */
    public function __construct(array $data,array $rules)
    {
        $this->_data = $data;
        if(!isset($rules['main'])){
            $rules = ['main' => $rules];
        }

        $this->_rules = $rules['main'];
        unset($rules['main']);

        $this->_components = $rules;

        if(empty($this->_rules)){
            throw new \Exception('校验规则不能为空',1001);
        }

        foreach ($this->_rules as &$rule){
            list($paramName,$checkType,$valueType) = $rule;

            $checkType = strtolower($checkType);
            $valueType = strtolower($valueType);
            if(empty($paramName) || !is_string($paramName) || !in_array($checkType,$this->_checkType) || empty($valueType)){
                $this->_invalidRules();
            }

            $rule = [$paramName,$checkType,$valueType];
        }
    }

    /**
     * @return array
     */
    public function check(){
        foreach ($this->_rules as $rule){
            list($paramName,$checkType,$valueType) = $rule;

            switch ($checkType){
                case self::CHECK_TYPE_REQUIRE :
                    $this->_checkRequire($paramName,$valueType);
                    break;
                case self::CHECK_TYPE_OPTIONAL :
                    $this->_checkOptional($paramName,$valueType);
                    break;
                case self::CHECK_TYPE_REPEATED :
                    $this->_checkRepeated($paramName,$valueType);
                    break;
                case self::CHECK_TYPE_REPEATED_REQUIRE :
                    $this->_checkRepeatedRequire($paramName,$valueType);
                    break;
                default:
            }
        }

        return $this->_formatData;
    }

    /**
     * @param string $paramName 参数名称
     * @param string $valueType 参数值类型
     */
    private function _checkRequire($paramName,$valueType){
        if(!isset($this->_data[$paramName])){
            $this->_invalidParam($paramName);
        }

        $this->_formatData[$paramName] = $this->_formatValue($this->_data[$paramName],$valueType);
        if(empty($this->_formatData[$paramName]) && $this->_formatData[$paramName] !== 0 && $this->_formatData[$paramName] !== floatval(0)){
            $this->_invalidParam($paramName);
        }
    }

    /**
     * @param $paramName
     * @param $valueType
     */
    private function _checkOptional($paramName,$valueType){
        if(!isset($this->_data[$paramName])){
            return;
        }

        $this->_formatData[$paramName] = $this->_formatValue($this->_data[$paramName],$valueType);
    }

    /**
     * @param $paramName
     * @param $valueType
     */
    private function _checkRepeated($paramName,$valueType){
        if(empty($this->_data[$paramName])){
            return;
        }

        if(!is_array($this->_data[$paramName])){
            $this->_invalidParam($paramName);
        }

        foreach ($this->_data[$paramName] as $item){
            $value = $this->_formatValue($item,$valueType);
            if(!empty($value)){
                $this->_formatData[$paramName][] = $value;
            }
        }
    }

    /**
     * @param $paramName
     * @param $valueType
     */
    private function _checkRepeatedRequire($paramName,$valueType){
        if(!isset($this->_data[$paramName])){
            $this->_invalidParam($paramName);
        }

        if(!is_array($this->_data[$paramName])){
            $this->_invalidParam($paramName);
        }

        foreach ($this->_data[$paramName] as $item){
            $value = $this->_formatValue($item,$valueType);
            if(!empty($value)){
                $this->_formatData[$paramName][] = $value;
            }
        }

        if(empty($this->_formatData[$paramName])){
            $this->_invalidParam($paramName);
        }
    }

    /**
     * @param $value
     * @param $valueType
     * @return bool|float|int|string
     */
    private function _formatValue($value,$valueType){
        switch ($valueType){
            case self::VALUE_TYPE_INT :
                $value = intval($value);
                break;
            case self::VALUE_TYPE_FLOAT :
                $value = floatval($value);
                break;
            case self::VALUE_TYPE_STRING :
                $value = trim($value);
                break;
            default :
                if(!isset($this->_components[$valueType])){
                    $this->_invalidRules();
                }
                $newRules = $this->_components;
                $newRules['main'] = $newRules[$valueType];
//                unset($newRules[$valueType]); 有可能结构本身嵌套自己，比如分类
                $value = (new self($value,$newRules))->check();
        }

        return $value;
    }

    private function _invalidParam($paramName = '')
    {
        $msg = '参数错误';
        if($paramName){
            $msg .= "【{$paramName}】";
        }
        throw new \Exception( $msg, 1003);
    }

    private function _invalidRules(){
        throw new \Exception('校验规则格式错误',1002);
    }
}