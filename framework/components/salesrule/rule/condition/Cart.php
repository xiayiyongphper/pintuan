<?php
namespace framework\components\salesrule\rule\condition;

use framework\components\salesrule\rule\ConditionAbstract;
use framework\db\readonly\models\core\Rule;
use service\components\sales\Quote;
use service\components\sales\RuleQuote;
use service\models\VarienObject;

class Cart extends ConditionAbstract
{
    public function loadAttributeOptions()
    {
        $attributes = array(
            'subtotal' => 'Subtotal',
            'total_qty' => 'Total Items Quantity',
        );

        $this->setAttributeOption($attributes);

        return $this;
    }


    public function getInputType()
    {
        switch ($this->getAttribute()) {
            case 'base_subtotal':
            case 'total_qty':
                return 'string';
            case 'weight':
                return 'numeric';
            case 'shipping_method':
            case 'payment_method':
            case 'country_id':
            case 'region_id':
                return 'select';
        }
        return 'string';
    }

    public function getValueElementType()
    {
        switch ($this->getAttribute()) {
            case 'shipping_method':
            case 'payment_method':
            case 'country_id':
            case 'region_id':
                return 'select';
        }
        return 'text';
    }

    /**
     * Author Jason Y.Wang
     * @param Quote $quote
     * @return bool|int|string
     */
    public function validate(VarienObject $quote)
    {
        $cart = new VarienObject();
        $cart->setSubtotal($quote->grandTotalToValidate);
        $cart->setTotalQty($quote->qtyToValidate);

        $result = parent::validate($cart);
        /** @var RuleQuote $quote */
        $quote->attribute = $this->getAttribute();
        $quote->_gap_to_next = $this->_gapToNext;
        $quote->_max_value = $this->_maxValue;
        if ($result === false) {
            switch ($this->getAttribute()) {
                case 'subtotal':
                    if ($quote->getRuleType() == Rule::TYPE_ITEM || $quote->getRuleType() == Rule::TYPE_GROUP) {
                        $quote->setUnavailableReason(Rule::UNAVAILABLE_REASON_3);
                    } else {
                        $quote->setUnavailableReason(Rule::UNAVAILABLE_REASON_1);
                    }
                    break;
                case 'total_qty':
                    if ($quote->getRuleType() == Rule::TYPE_ITEM || $quote->getRuleType() == Rule::TYPE_GROUP) {
                        $quote->setUnavailableReason(Rule::UNAVAILABLE_REASON_6);
                    } else {
                        $quote->setUnavailableReason(Rule::UNAVAILABLE_REASON_2);
                    }
                    break;
            }
        }
        return $result;
    }
}
