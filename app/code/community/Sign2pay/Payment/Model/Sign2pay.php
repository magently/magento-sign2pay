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
        $session = Mage::getSingleton('checkout/session');
        $order = Mage::getModel('sales/order')->loadByIncrementId($session->getLastRealOrderId());

        Mage::helper('sign2pay')->setStatusOnOrder(
            $order, Mage::getStoreConfig('payment/sign2pay/order_status', Mage::app()->getStore()));
        
        //return Mage::getModel('sign2pay/processor')->_verifyResponse('5648f8b20652686ecf0003e5');
        //return Mage::getModel('sign2pay/processor')->processPaymentCaptureResponse(array('purchase_id' => '5649a6a90652686ecf000638'));
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
