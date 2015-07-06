<?php
class Scompany_Sign2pay_Block_Redirect extends Mage_Core_Block_Template
{
    /**
     * @var Mage_Sales_Model_Order
     */
    protected $_order;

    /**
     * Set apropriate template, attach sign2pay scripts
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $this->setTemplate('sign2pay/redirect.phtml');
        Mage::helper('sign2pay')->attachPaymentScripts(array(
            'initialize' => true
        ));

        return $this;
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        $order = $this->getOrder();

        if (!$order->getId() || $order->getPayment()->getMethodInstance()->getCode() != 'sign2pay') {
            return $this->__('There is no order to be payed with Sign2Pay.');
        }

        return parent::_toHtml();
    }

    /**
     * Get order
     *
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        if (!$this->_order) {
            // Get the order id
            $order_id = Mage::getSingleton('checkout/session')->getLastRealOrderId();
            // Set the order model
            $this->_order = Mage::getModel('sales/order')->loadByIncrementId($order_id);
        }

        return $this->_order;
    }
}
