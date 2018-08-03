<?php

namespace service\tools\quote;



class Discount
{

    protected $_calculator;

    public function __construct()
    {
        $this->_calculator = new Validator();
    }

    /**
     * Collect address discount amount
     *
     * @param   Quote $quote
     * @return  $this
     */
    public function collect(Quote $quote)
    {
        $this->_calculator->setQuote($quote)
            ->init()->initTotals();
        return $this;
    }
}
