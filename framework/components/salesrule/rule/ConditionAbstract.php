<?php
namespace framework\components\salesrule\rule;

use service\models\VarienObject;


abstract class ConditionAbstract
    extends VarienObject
    implements ConditionInterface
{

    protected $_inputType = null;
    protected $_defaultOperatorOptions = null;
    protected $_defaultOperatorInputByType = null;
    protected $_arrayInputTypes = array();
    protected $_rule;
    public $_gapToNext = -1;
    public $_maxValue = 0;

    public function __construct()
    {
        parent::__construct();

        $this->loadAttributeOptions()->loadOperatorOptions()->loadValueOptions();

        if ($options = $this->getAttributeOptions()) {
            foreach ($options as $attr => $dummy) {
                $this->setAttribute($attr);
                break;
            }
        }
        if ($options = $this->getOperatorOptions()) {
            foreach ($options as $operator => $dummy) {
                $this->setOperator($operator);
                break;
            }
        }
    }


    public function prepareConditionSql()
    {
        return '';
    }

    public function getDefaultOperatorInputByType()
    {
        if (null === $this->_defaultOperatorInputByType) {
            $this->_defaultOperatorInputByType = array(
                'string' => array('==', '!=', '>=', '>', '<=', '<', '{}', '!{}', '()', '!()'),
                'numeric' => array('==', '!=', '>=', '>', '<=', '<', '()', '!()'),
                'date' => array('==', '>=', '<='),
                'select' => array('==', '!='),
                'boolean' => array('==', '!='),
                'multiselect' => array('{}', '!{}', '()', '!()'),
                'grid' => array('()', '!()'),
            );
            $this->_arrayInputTypes = array('multiselect', 'grid');
        }
        return $this->_defaultOperatorInputByType;
    }

    public function getDefaultOperatorOptions()
    {
        if (null === $this->_defaultOperatorOptions) {
            $this->_defaultOperatorOptions = array(
                '==' => 'is',
                '!=' => 'is not',
                '>=' => 'equals or greater than',
                '<=' => 'equals or less than',
                '>' => 'greater than',
                '<' => 'less than',
                '{}' => 'contains',
                '!{}' => 'does not contain',
                '()' => 'is one of',
                '!()' => 'is not one of'
            );
        }
        return $this->_defaultOperatorOptions;
    }

    public function getForm()
    {
        return $this->getRule()->getForm();
    }


    public function loadArray($arr)
    {
        $this->setType($arr['type']);
        $this->setAttribute(isset($arr['attribute']) ? $arr['attribute'] : false);
        $this->setOperator(isset($arr['operator']) ? $arr['operator'] : false);
        $this->setValue(isset($arr['value']) ? $arr['value'] : false);
        $this->setIsValueParsed(isset($arr['is_value_parsed']) ? $arr['is_value_parsed'] : false);

        return $this;
    }

    public function loadXml($xml)
    {
        if (is_string($xml)) {
            $xml = simplexml_load_string($xml);
        }
        $arr = (array)$xml;
        $this->loadArray($arr);
        return $this;
    }

    public function loadAttributeOptions()
    {
        return $this;
    }

    public function getAttributeOptions()
    {
        return array();
    }

    public function getAttributeSelectOptions()
    {
        $opt = array();
        foreach ($this->getAttributeOption() as $k => $v) {
            $opt[] = array('value' => $k, 'label' => $v);
        }
        return $opt;
    }

    public function getAttributeName()
    {
        return $this->getAttributeOption($this->getAttribute());
    }

    public function loadOperatorOptions()
    {
        $this->setOperatorOption($this->getDefaultOperatorOptions());
        $this->setOperatorByInputType($this->getDefaultOperatorInputByType());
        return $this;
    }

    public function getInputType()
    {
        if (null === $this->_inputType) {
            return 'string';
        }
        return $this->_inputType;
    }

    public function getOperatorSelectOptions()
    {
        $type = $this->getInputType();
        $opt = array();
        $operatorByType = $this->getOperatorByInputType();
        foreach ($this->getOperatorOption() as $k => $v) {
            if (!$operatorByType || in_array($k, $operatorByType[$type])) {
                $opt[] = array('value' => $k, 'label' => $v);
            }
        }
        return $opt;
    }

    public function getOperatorName()
    {
        return $this->getOperatorOption($this->getOperator());
    }

    public function loadValueOptions()
    {
        $this->setValueOption(array());
        return $this;
    }

    public function getValueSelectOptions()
    {
        $valueOption = $opt = array();
        if ($this->hasValueOption()) {
            $valueOption = (array)$this->getValueOption();
        }
        foreach ($valueOption as $k => $v) {
            $opt[] = array('value' => $k, 'label' => $v);
        }
        return $opt;
    }

    public function getValueParsed()
    {
        if (!$this->hasValueParsed()) {
            $value = $this->getData('value');
            if ($this->isArrayOperatorType() && is_string($value)) {
                $value = preg_split('#\s*[,;]\s*#', $value, null, PREG_SPLIT_NO_EMPTY);
            }
            $this->setValueParsed($value);
        }
        return $this->getData('value_parsed');
    }

    public function isArrayOperatorType()
    {
        $op = $this->getOperator();
        return $op === '()' || $op === '!()' || in_array($this->getInputType(), $this->_arrayInputTypes);
    }

    public function getNewChildSelectOptions()
    {
        return array(
            array('value' => '', 'label' => 'Please choose a condition to add...'),
        );
    }

    public function getNewChildName()
    {
        return $this->getAddLinkHtml();
    }


    public function getTypeElement()
    {
        return $this->getForm()->addField($this->getPrefix() . '__' . $this->getId() . '__type', 'hidden', array(
            'name' => 'rule[' . $this->getPrefix() . '][' . $this->getId() . '][type]',
            'value' => $this->getType(),
            'no_span' => true,
            'class' => 'hidden',
        ));
    }

    public function getTypeElementHtml()
    {
        return $this->getTypeElement()->getHtml();
    }


    public function getOperatorElementHtml()
    {
        return $this->getOperatorElement()->getHtml();
    }

    public function getValueElementType()
    {
        return 'text';
    }



    public function asString($format = '')
    {
        $str = $this->getAttributeName() . ' ' . $this->getOperatorName() . ' ' . $this->getValueName();
        return $str;
    }

    public function asStringRecursive($level = 0)
    {
        $str = str_pad('', $level * 3, ' ', STR_PAD_LEFT) . $this->asString();
        return $str;
    }

    /**
     * Author Jason Y.Wang
     * @param $validatedValue 传入需要验证的数值
     * @return bool|int|string
     *
     */
    public function validateAttribute($validatedValue)
    {
        if (is_object($validatedValue)) {
            return false;
        }

        $value = $this->getValueParsed();

        $op = $this->getOperatorForValidate();

        // operator要求数组，value不是数组，false
        if ($this->isArrayOperatorType() && !is_array($value)) {
            return false;
        }

        // operator要求数组，value是数组，则构造循环
        if ($this->isArrayOperatorType() && !is_array($value)) {
            $value_list = array($value);
        }

        // operator非数组，value不是数组，则循环验证
        if (!$this->isArrayOperatorType() && !is_array($value)) {
            //// value有逗号分隔，
            //if(strpos($value, ',')!==false || strpos($value, '；')!==false){
            $value_list = preg_split('#\s*[,;]\s*#', $value, null, PREG_SPLIT_NO_EMPTY);
            //}
        }
        if ($this->isArrayOperatorType() && is_array($value)) {
            $value_list = array($value);
        }

        // 分级验证，找到最后一个符合要求的级数，遇到不符合，直接退回
        $level = -1;// 当前级数
        $this->_maxValue = end($value_list);
        reset($value_list);
        foreach ($value_list as $index => $value) {
            $result = $this->validateOneValue($validatedValue, $value, $op);
            if ($result) {
                // 若符合，则记下当前级数
                $level = $index;
            } else {
                //满足最高级时，不会到这个分支，_gapToNext：-1  最低级则显示差距
                $this->_gapToNext = $value - $validatedValue;
                // 否则，直接退出循环
                break;
            }
        }
        // index从0开始，所以返回+1的值
        if ($level != -1) {
            return $level + 1;
        } else {
            return false;
        }

    }

    protected function validateOneValue($validatedValue, $value, $op)
    {

        switch ($op) {
            case '==':
            case '!=':
                if (is_array($value)) {
                    if (is_array($validatedValue)) {
                        $result = array_intersect($value, $validatedValue);
                        $result = !empty($result);
                    } else {
                        return false;
                    }
                } else {
                    if (is_array($validatedValue)) {
                        $result = count($validatedValue) == 1 && array_shift($validatedValue) == $value;
                    } else {
                        $result = $this->_compareValues($validatedValue, $value);
                    }
                }
                break;

            case '<=':
            case '>':
                if (!is_scalar($validatedValue)) {
                    return false;
                } else {
                    $result = $validatedValue <= $value;
                }
                break;

            case '>=':
            case '<':
                if (!is_scalar($validatedValue)) {
                    return false;
                } else {
                    $result = $validatedValue >= $value;
                }
                break;

            case '{}':
            case '!{}':
                if (is_scalar($validatedValue) && is_array($value)) {
                    foreach ($value as $item) {
                        if (stripos($validatedValue, $item) !== false) {
                            $result = true;
                            break;
                        }
                    }
                } elseif (is_array($value)) {
                    if (is_array($validatedValue)) {
                        $result = array_intersect($value, $validatedValue);
                        $result = !empty($result);
                    } else {
                        return false;
                    }
                } else {
                    if (is_array($validatedValue)) {
                        $result = in_array($value, $validatedValue);
                    } else {
                        $result = $this->_compareValues($value, $validatedValue, false);
                    }
                }
                break;

            case '()':
            case '!()':
                if (is_array($validatedValue)) {
                    $result = count(array_intersect($validatedValue, (array)$value)) > 0;
                } else {
                    $value = (array)$value;
                    foreach ($value as $item) {
                        if ($this->_compareValues($validatedValue, $item)) {
                            $result = true;
                            break;
                        }
                    }
                }
                break;
        }

        if ('!=' == $op || '>' == $op || '<' == $op || '!{}' == $op || '!()' == $op) {
            $result = !$result;
        }

        return $result;

    }

    protected function _compareValues($validatedValue, $value, $strict = true)
    {
        if ($strict && is_numeric($validatedValue) && is_numeric($value)) {
            return $validatedValue == $value;
        } else {
            $validatePattern = preg_quote($validatedValue, '~');
            if ($strict) {
                $validatePattern = '^' . $validatePattern . '$';
            }
            return (bool)preg_match('~' . $validatePattern . '~iu', $value);
        }
    }

    public function validate(VarienObject $object)
    {
        return $this->validateAttribute($object->getData($this->getAttribute()));
    }

    public function getOperatorForValidate()
    {
        return $this->getOperator();
    }


    /**
     * 根据值判断一个属性是否属于多级规则
     * @param $condition
     * @return bool
     */
    public function isMultiLevelAttribute($condition)
    {
        switch ($condition->getOperator()) {
            case '>=':
            case '>':
            case '<=':
            case '<':
                $value_list = preg_split('#\s*[,;]\s*#', $condition->getValue(), null, PREG_SPLIT_NO_EMPTY);
                $flag = count($value_list) > 1 ? true : false;
                break;
            default:
                $flag = false;

        }
        return $flag;
    }

    /**
     * @return Rule
     */
    public function getRule()
    {
        return $this->_rule;
    }

    /**
     * @param $rule
     * @return $this
     */
    public function setRule($rule)
    {
        $this->_rule = $rule;
        return $this;
    }

}
