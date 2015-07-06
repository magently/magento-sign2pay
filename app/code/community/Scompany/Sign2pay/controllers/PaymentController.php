<?php

class Scompany_Sign2pay_PaymentController extends Mage_Core_Controller_Front_Action
{
    /**
     * Redirect action after placing an order with Sign2Pay payment
     */
    public function redirectAction()
    {
        $session = Mage::getSingleton('checkout/session');
        $session->setSign2payQuoteId($session->getQuoteId());
        $session->unsQuoteId();
        $session->unsRedirectUrl();

        $order_id = Mage::getSingleton('checkout/session')->getLastRealOrderId();
        $order = Mage::getModel('sales/order')->loadByIncrementId($order_id);
        $order->setState(Mage::getStoreConfig('payment/sign2pay/order_status', Mage::app()->getStore()));
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
                $order->cancel()->save();
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
        $quote = Mage::getModel('sales/quote')->load($session->getSign2payQuoteId() ? $session->getSign2payQuoteId() : $session->getQuoteId());

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
        $options['ref_id']         = ($options['email'] && $options['last_name']) ? ($options['email'] . ',' + $options['last_name'] . ',' . time()) : 'leeg';
        $options['amount']         = $quote->getGrandTotal() * 100;

        $jsonData = json_encode($options);
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody($jsonData);
    }
}
