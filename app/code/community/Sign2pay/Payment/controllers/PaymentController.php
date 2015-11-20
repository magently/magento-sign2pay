<?php

class Sign2pay_Payment_PaymentController extends Mage_Core_Controller_Front_Action
{
    /**
     * Redirect action after placing an order with Sign2Pay payment
     */
    public function redirectAction()
    {
        $session = Mage::getSingleton('checkout/session');

        if (!$session->getQuoteId()) {
            if (!$session->getSign2payQuoteId()) {
                return $this->_redirect('checkout/cart');
            }
        } else {
            $session->setSign2payQuoteId($session->getQuoteId());
            $session->unsQuoteId();
            $session->unsRedirectUrl();
        }

        $orderId = Mage::getSingleton('checkout/session')->getLastRealOrderId();
        $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);

        if (!$order->getId() || $order->getPayment()->getMethodInstance()->getCode() != 'sign2pay') {
            Mage::getSingleton('checkout/session')->addError("There is no order pending a payment.");
            return $this->_redirect('checkout/cart');
        }

        Mage::helper('sign2pay')->setStatusOnOrder($order, Mage::getStoreConfig('payment/sign2pay/order_status', Mage::app()->getStore()));
        $order->save();

        $this->_redirectUrl(Mage::helper('sign2pay')->getSign2PayInitialRequest());
    }


    /**
     * Response action is triggered when your gateway sends
     * back a response after the initial request
     */
    public function responseAction()
    {
        try {
            if (!$this->getRequest()->isGet()) {
                throw new Exception('Wrong request method');
            }
            $data = $this->getRequest()->getParams();
            $payment = Mage::getModel('sign2pay/processor')->performPayment($data);            

        } catch (Exception $e) {
            Mage::logException($e);
            $this->getResponse()->setHttpResponseCode(500);
        }
    }

    /**
     * Success action after gateway response
     */
    public function successAction()
    {
        Mage::getSingleton('checkout/session')->getQuote()->setIsActive(false)->save();
        $this->_redirect('checkout/onepage/success', array('_secure'=>true));
    }

    /*
     * Failure action after gateway response
     */
    public function failureAction()
    {
        $session = Mage::getSingleton('checkout/session');
        $session->setQuoteId($session->getSign2payQuoteId(true));
        Mage::getSingleton('checkout/session')->getQuote()->setIsActive(false)->save();
        $this->_redirect('checkout/onepage/failure', array('_secure'=>true));
    }

    /**
     * When a customer cancel payment from Sign2Pay or an error occurs.
     *
     * @todo This should be processed by the payment processor
     */
    public function cancelAction()
    {
        $session = Mage::getSingleton('checkout/session');
        $session->setQuoteId($session->getSign2payQuoteId(true));
        if ($session->getLastRealOrderId()) {
            $order = Mage::getModel('sales/order')->loadByIncrementId($session->getLastRealOrderId());
            if ($order->getId()) {
                Mage::getModel('sign2pay/processor')->cancel($order);
            }
            Mage::helper('sign2pay/checkout')->restoreQuote();
            Mage::getSingleton('checkout/session')->addError("The payment has been cancelled.");

        }
        $this->_redirect('checkout/cart');
    }

}
