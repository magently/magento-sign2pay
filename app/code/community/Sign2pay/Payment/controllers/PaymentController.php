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

        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Response action is triggered when your gateway sends
     * back a response after processing the customer's payment
     */
    public function responseAction()
    {
        if (!$this->getRequest()->isPost()) {
            return;
        }

        try {
            $data = $this->getRequest()->getPost();
            Mage::getModel('sign2pay/processor')->processRequest($data);
            exit;
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
        $session = Mage::getSingleton('checkout/session');
        $session->setQuoteId($session->getSign2payQuoteId(true));
        Mage::getSingleton('checkout/session')->getQuote()->setIsActive(false)->save();
        $this->_redirect('checkout/onepage/success', array('_secure'=>true));
    }

    /**
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
     * When a customer cancel payment from Sign2Pay.
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
            Mage::getSingleton('checkout/session')->addError("You've cancelled the Sign2Pay screen.");
        }
        $this->_redirect('checkout/cart');
    }

    /**
     * Fetch payment related options required to process Sign2Pay payment
     *
     * @todo Check if this should process also order
     */
    public function fetchPaymentOptionsAction()
    {
        $session = Mage::getSingleton('checkout/session');
        $quote = null;

        if ($orderId = $session->getLastRealOrderId()) {
            $order = Mage::getModel('sales/order')->loadByIncrementId($order);
            $quote = $order->getQuote();
        }

        if (!$quote) {
            $quote = Mage::getModel('sales/quote')->load($session->getSign2payQuoteId() ? $session->getSign2payQuoteId() : $session->getQuoteId());
        }

        $billaddress = $quote->getBillingAddress();

        $options = array();
        $options['checkout_type']  = "multi";
        $options['first_name']     = $billaddress->getFirstname();
        $options['last_name']      = $billaddress->getLastname();
        $options['email']          = $billaddress->getEmail();
        $options['address']        = implode(' ', (array) $billaddress->getStreet());
        $options['city']           = $billaddress->getCity();
        $options['country']        = $billaddress->getCountry();
        $options['postal_code']    = $billaddress->getPostcode();
        $options['amount']         = $quote->getGrandTotal() * 100;

        if ($orderId = $quote->getReservedOrderId()) {
            $options['ref_id']     = $orderId;
        } else {
            $options['ref_id']     = ($options['email'] && $options['last_name']) ? ($options['email'] . ',' + $options['last_name'] . ',' . time()) : 'leeg';
        }

        $jsonData = json_encode($options);
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody($jsonData);
    }
}
