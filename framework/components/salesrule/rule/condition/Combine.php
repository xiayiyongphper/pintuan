<?php
namespace framework\components\salesrule\rule\condition;

use framework\components\Constants;
use framework\components\salesrule\rule\ConditionAbstract;
use framework\components\ToolsAbstract;
use service\models\VarienObject;

/**
 * Class Combine
 * @package service\models\salesrule\rule\condition
 * @method getAggregator()
 * @method getPrefix()
 * @method setPrefix()
 */
class Combine extends ConditionAbstract
{
    /**
     * Store all used condition models
     *
     * @var array
     */
    static protected $_conditionModels = array();

    /**
     * Prepare sql where by condition
     *
     * @return string
     */
    public function prepareConditionSql()
    {
        $wheres = array();
        foreach ($this->getConditions() as $condition) {
            /** @var $condition ConditionAbstract */
            $wheres[] = $condition->prepareConditionSql();
        }

        if (empty($wheres)) {
            return '';
        }
        $delimiter = $this->getAggregator() == "all" ? ' AND ' : ' OR ';
        return ' (' . implode($delimiter, $wheres) . ') ';
    }

    /**
     * Retrieve new object for each requested model.
     * If model is requested first time, store it at static array.
     *
     * It's made by performance reasons to avoid initialization of same models each time when rules are being processed.
     *
     * @param  string $modelClass
     * @return ConditionAbstract|bool
     */
    protected function _getNewConditionModelInstance($modelClass)
    {
        if (empty($modelClass)) {
            return false;
        }

        if (!array_key_exists($modelClass, self::$_conditionModels)) {
            $parts = preg_split('/[\/\_]/', $modelClass);
//            array_unshift($parts, 'sales');
            array_unshift($parts, 'components');
            array_unshift($parts, 'framework');
            $name = array_pop($parts);
            array_push($parts, ucfirst($name));
            $modelClass = implode('\\', $parts);
            $model = new $modelClass();
            self::$_conditionModels[$modelClass] = $model;
        } else {
            $model = self::$_conditionModels[$modelClass];
        }

        if (!$model) {
            return false;
        }

        $newModel = clone $model;
        return $newModel;
    }

    public function __construct()
    {
        parent::__construct();
        $this->setType('rule/condition_combine')
            ->setAggregator('all')
            ->setValue(true)
            ->setConditions(array())
            ->setActions(array());


        $this->loadAggregatorOptions();
        if ($options = $this->getAggregatorOptions()) {
            foreach ($options as $aggregator => $dummy) {
                $this->setAggregator($aggregator);
                break;
            }
        }
    }

    /* start aggregator methods */
    public function loadAggregatorOptions()
    {
        $this->setAggregatorOption(array(
            'all' => 'ALL',
            'any' => 'ANY',
        ));
        return $this;
    }

    public function getAggregatorSelectOptions()
    {
        $opt = array();
        foreach ($this->getAggregatorOption() as $k => $v) {
            $opt[] = array('value' => $k, 'label' => $v);
        }
        return $opt;
    }

    public function getAggregatorName()
    {
        return $this->getAggregatorOption($this->getAggregator());
    }

    /* end aggregator methods */

    public function loadValueOptions()
    {
        $this->setValueOption(array(
            1 => 'TRUE',
            0 => 'FALSE',
        ));
        return $this;
    }

    public function addCondition($condition)
    {
        $condition->setRule($this->getRule());
        $condition->setObject($this->getObject());
        $condition->setPrefix($this->getPrefix());

        $conditions = $this->getConditions();
        $conditions[] = $condition;

        if (!$condition->getId()) {
            $condition->setId($this->getId() . '--' . sizeof($conditions));
        }

        $this->setData($this->getPrefix(), $conditions);
        return $this;
    }

    public function getValueElementType()
    {
        return 'select';
    }

    /**
     * Returns array containing conditions in the collection
     *
     * Output example:
     * array(
     *   'type'=>'combine',
     *   'operator'=>'ALL',
     *   'value'=>'TRUE',
     *   'conditions'=>array(
     *     {condition::asArray},
     *     {combine::asArray},
     *     {quote_item_combine::asArray}
     *   )
     * )
     *
     * @return array
     */
    public function asArray(array $arrAttributes = array())
    {
        $out = parent::asArray();
        $out['aggregator'] = $this->getAggregator();

        foreach ($this->getConditions() as $condition) {
            $out['conditions'][] = $condition->asArray();
        }

        return $out;
    }

    public function asXml($containerKey = 'conditions', $itemKey = 'condition')
    {
        $xml = "<aggregator>" . $this->getAggregator() . "</aggregator>"
            . "<value>" . $this->getValue() . "</value>"
            . "<$containerKey>";
        foreach ($this->getConditions() as $condition) {
            $xml .= "<$itemKey>" . $condition->asXml() . "</$itemKey>";
        }
        $xml .= "</$containerKey>";
        return $xml;
    }

    public function loadArray($arr, $key = 'conditions')
    {
        $this->setAggregator(isset($arr['aggregator']) ? $arr['aggregator']
            : (isset($arr['attribute']) ? $arr['attribute'] : null))
            ->setValue(isset($arr['value']) ? $arr['value']
                : (isset($arr['operator']) ? $arr['operator'] : null));

        if (!empty($arr[$key]) && is_array($arr[$key])) {

            foreach ($arr[$key] as $condArr) {
                try {
                    $cond = $this->_getNewConditionModelInstance($condArr['type']);
                    if ($cond) {
                        $this->addCondition($cond);
                        $cond->loadArray($condArr, $key);
                    }
                } catch (\Exception $e) {
                    ToolsAbstract::logException($e);
                }
            }
        }
        return $this;
    }

    public function loadXml($xml)
    {
        if (is_string($xml)) {
            $xml = simplexml_load_string($xml);
        }
        $arr = parent::loadXml($xml);
        foreach ($xml->conditions->children() as $condition) {
            $arr['conditions'] = parent::loadXml($condition);
        }
        $this->loadArray($arr);
        return $this;
    }


    public function asString($format = '')
    {
        $str = sprintf("If %s of these conditions are %s:", $this->getAggregatorName(), $this->getValueName());
        return $str;
    }

    public function asStringRecursive($level = 0)
    {
        $str = parent::asStringRecursive($level);
        foreach ($this->getConditions() as $cond) {
            $str .= "\n" . $cond->asStringRecursive($level + 1);
        }
        return $str;
    }

    /**
     * 所有条件全部为单级规则，返回值为boolean
     * 条件中存在一个多级规则，返回值为该规则的等级
     * 条件中存在多个多级规则，返回值为多级规则中等级最小的那个
     * @param VarienObject $object
     * @return bool|int|mixed
     */
    public function validate(VarienObject $object)
    {
        // 若无条件，则直接返回ok。
        if (!$this->getConditions()) {
            return true;
        }

        $all = $this->getAggregator() === 'all';
        $true = (bool)$this->getValue();

        /**
         * 检测是否符合条件，若不符合则返回false，符合则返回符合的级数，从1开始，234以此类推
         * 同一个规则里，有多个条件的，只有多级条件的结果级数小于之前计算的级数才可以覆盖
         */

        /** @var Cart $cond */
        $level = Constants::INT_MAX;
        $hasMultiLevel = false;

        foreach ($this->getConditions() as $cond) {
            // 要检测的属性，如subtotal
            // 查看属性是否多级，需要在对应的condition类里定义
            $isMultiLevelAttribute = $cond->isMultiLevelAttribute($cond);
            if ($isMultiLevelAttribute) {
                $hasMultiLevel = true;
            }
            // 检验结果  调用cart中的validate
            $t = $cond->validate($object);
            $all = $all && $t;
            // 按条件覆盖
            if ($all === false) {
                break;
            }
            if ($isMultiLevelAttribute) {
                $level = min($level, $t);
            }
        }
        return $all ? ($hasMultiLevel ? $level : true) : false;// 原本返回true。这里返回级数
    }

    public function setJsFormObject($form)
    {
        $this->setData('js_form_object', $form);
        foreach ($this->getConditions() as $condition) {
            $condition->setJsFormObject($form);
        }
        return $this;
    }

    /**
     * Get conditions, if current prefix is undefined use 'conditions' key
     *
     * @return array
     */
    public function getConditions()
    {
        $key = $this->getPrefix() ? $this->getPrefix() : 'conditions';
        return $this->getData($key);
    }

    /**
     * Set conditions, if current prefix is undefined use 'conditions' key
     *
     * @param array $conditions
     * @return $this
     */
    public function setConditions($conditions)
    {
        $key = $this->getPrefix() ? $this->getPrefix() : 'conditions';
        return $this->setData($key, $conditions);
    }

    /**
     * Getter for "Conditions Combination" select option for recursive combines
     */
    protected function _getRecursiveChildSelectOption()
    {
        return array('value' => $this->getType(), 'label' => 'Conditions Combination');
    }

}
