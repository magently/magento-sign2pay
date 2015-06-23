<?php
class Scompany_Sign2pay_Block_Form_Sign2pay extends Mage_Payment_Block_Form
{
	protected function _construct()
	{
		parent::_construct();
		$this->setTemplate('sign2pay/form/sign2pay.phtml');
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