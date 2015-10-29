<?php

class Sign2pay_Payment_Model_Sign2pay extends Mage_Payment_Model_Method_Abstract
{
    protected $_code = 'sign2pay';
    protected $_formBlockType = 'sign2pay/form_sign2pay';
    protected $_infoBlockType = 'sign2pay/info_sign2pay';
    protected $_isInitializeNeeded      = true;
    protected $_canUseInternal          = true;
    protected $_canUseForMultishipping  = false;

    public function getOrderPlaceRedirectUrl()
    {
        //return Mage::getUrl('sign2pay/payment/redirect', array('_secure' => true));

        return Mage::helper('sign2pay')->getSign2PayInitialRequest();
    }

    /**
     * Check method for processing with base currency
     *
     * @param string $currencyCode
     * @return boolean
     */
    public function canUseForCurrency($currencyCode)
    {
        return $currencyCode == 'EUR';
    }
}
