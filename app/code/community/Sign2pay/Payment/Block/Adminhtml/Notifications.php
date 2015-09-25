<?php

class Sign2pay_Payment_Block_Adminhtml_Notifications extends Mage_Adminhtml_Block_Template
{
    public function getMessage()
    {
        try {
            $payments = Mage::getSingleton('payment/config')->getActiveMethods();

            if (!isset($payments['sign2pay'])) return '';

            foreach (Mage::app()->getStore()->getAvailableCurrencyCodes(true) as $code) {
                if ($code != 'EUR') {
                    return Mage::helper('sign2pay')->__('Sign2Pay only support payments in Euro. It won\'t be available for orders in other currencies.');
                }
            }

            return '';
        } catch (Exception $e) {
            return '';
        }
    }
}
