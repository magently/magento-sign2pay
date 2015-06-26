<?php
class Scompany_Sign2pay_Block_Form_Sign2pay extends Mage_Payment_Block_Form
{
    protected function _construct()
    {
        parent::_construct();

        // This block might not be constructed
        // with whole magento layout
        if (Mage::app()->getLayout()->getBlock('head')) {
            Mage::helper('sign2pay')->attachPaymentScripts();
        }
    }

    public function getOrderId()
    {
        $lastOrderId = Mage::getSingleton('checkout/session')->getLastOrderId();
        return $lastOrderId;
    }

    public function getFullOrderID()
    {
        $lastOrderId = Mage::getSingleton('checkout/session')->getLastOrderId();
        $order = Mage::getModel('sales/order')->load($lastOrderId);
        $paymentTransactionId = $order->getIncrementId();
        return $paymentTransactionId;
    }
}
