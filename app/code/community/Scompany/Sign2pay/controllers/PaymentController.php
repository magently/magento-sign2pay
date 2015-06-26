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

        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Response action is triggered when your gateway sends
     * back a response after processing the customer's payment
     *
     * @todo Add gateway authentication
     * @todo Add order state validation
     */
    public function responseAction()
    {
        $request = $this->getRequest();

        $apiKey =  Mage::getStoreConfig('payment/sign2pay/api_token', Mage::app()->getStore());

        $orderId    = $request->getPost('ref_id');
        $merchantId = $request->getPost('merchant_id');
        $purchaseId = $request->getPost('purchase_id');
        $refId      = $request->getPost('ref_id');
        $amount     = $request->getPost('amount');
        $status     = $request->getPost('status');
        $token      = $request->getPost('token');
        $timestamp  = $request->getPost('timestamp');
        $signature  = $request->getPost('signature');

        $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);

        if ($this->verifyResponse($apiKey, $token, $timestamp, $signature) && $status == 'mandate_valid') {
            // Payment was successful, so update the order's state
            // and send order email and move to the success page
            $redirect = Mage::getBaseUrl() . 'sign2pay/payment/success';

            $order->setState(
                Mage_Sales_Model_Order::STATE_PROCESSING,
                true,
                'Sign2pay has authorized the payment. with ID = ' . $purchaseId
            );

            $order->sendNewOrderEmail();
            $order->setEmailSent(true);

            $order->save();

            $arr = array('status' => "success",
                'redirect_to' => $redirect,
                'params' => array(
                    "total" => $amount,
                    "id" => $refId,
                    "purchase_id" => $purchaseId,
                    "signature" => true,
                    "status" => $status,
                    "authorization" => $timestamp
                )
            );
        } else {
            // There is a problem in the response we got
            $redirect = Mage::getBaseUrl() . 'sign2pay/payment/failure';

            $order->cancel()->setState(
                Mage_Sales_Model_Order::STATE_CANCELED,
                true,
                'Sign2pay has declined the payment.'
            )->save();

            $arr = array('status' => "failure",
                'redirect_to' => $redirect,
                'params' => array(
                    "ref_id" => $refId,
                    "message" => "Sorry, but we could not process your payment at this time. Your Order is still Pending.",
                )
            );
        }

        $anwser = json_encode($arr);
        $anwser = str_replace("\\/", "/", $anwser);
        echo $anwser;
    }

    /**
     * Success action after gateway response
     */
    public function  successAction()
    {
        $session = Mage::getSingleton('checkout/session');
        $session->setQuoteId($session->getSign2payQuoteId(true));
        Mage::getSingleton('checkout/session')->getQuote()->setIsActive(false)->save();
        $this->_redirect('checkout/onepage/success', array('_secure'=>true));
    }

    /**
     * Failure action after gateway response
     */
    public function  failureAction()
    {
        $session = Mage::getSingleton('checkout/session');
        $session->setQuoteId($session->getSign2payQuoteId(true));
        Mage::getSingleton('checkout/session')->getQuote()->setIsActive(false)->save();
        $this->_redirect('checkout/onepage/failure', array('_secure'=>true));
    }

    /**
     * When a customer cancel payment from Sign2Pay.
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
     * Veritfy the gateway response
     */
    protected function verifyResponse($api_key, $token, $timestamp, $signature)
    {
        return $signature === hash_hmac(
            "sha256",
            $timestamp . $token,
            $api_key
        );
    }

    /**
     * Fetch payment related options required to process Sign2Pay payment
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
