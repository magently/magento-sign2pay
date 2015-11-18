<?php

class Sign2pay_Payment_Block_Form_Sign2pay extends Mage_Payment_Block_Form
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('sign2pay/form/sign2pay.phtml')
            ->setMethodLabelAfterHtml('<img src="https://app.sign2pay.com/api/v2/banks/logo.gif" alt="Sign2pay" height="20" style="margin-right: 0.4em;"/>');
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
