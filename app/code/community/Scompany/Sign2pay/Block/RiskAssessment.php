<?php

class Scompany_Sign2pay_Block_RiskAssessment extends Mage_Core_Block_Template
{
    protected $_order;

    protected function _construct()
    {
        $this->setTemplate('sign2pay/riskassessment.phtml');
        Mage::helper('sign2pay')->attachPaymentScripts();
    }
}
