<?php

class Scompany_Sign2pay_Block_RiskAssessment extends Mage_Core_Block_Template
{
    protected $_order;

    protected function __construct()
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
