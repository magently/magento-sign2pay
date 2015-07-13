<?php

class Sign2pay_Payment_Block_RiskAssessment extends Mage_Core_Block_Template
{
    protected $_order;

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('sign2pay/riskassessment.phtml');
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        Mage::helper('sign2pay')->attachPaymentScripts();
    }
}
