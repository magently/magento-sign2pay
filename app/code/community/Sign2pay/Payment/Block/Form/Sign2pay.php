<?php

class Sign2pay_Payment_Block_Form_Sign2pay extends Mage_Payment_Block_Form
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('sign2pay/form/sign2pay.phtml');

        if (Mage::getStoreConfig('payment/sign2pay/logo_enabled', Mage::app()->getStore())) {
            $this
                ->setMethodLabelAfterHtml($this->_getMarkHtml())
                ->setMethodTitle('&nbsp;' . Mage::getStoreConfig('payment/sign2pay/title', Mage::app()->getStore()));
        }
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        // This block might not be constructed
        // with whole magento layout
        if (Mage::app()->getLayout()->getBlock('head')) {
            Mage::app()->getLayout()->getBlock('content')->append(
                Mage::app()->getLayout()->createBlock('sign2pay/riskAssessment')
            );
        }
    }

    protected function _getMarkHtml()
    {
        $height = Mage::getStoreConfig('payment/sign2pay/logo_width', Mage::app()->getStore());
        return '<img id="sign2pay-mark" src="' . Mage::helper('sign2pay')->getPaymentLogoUrl() . '" height="' . $height . '" />';
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
