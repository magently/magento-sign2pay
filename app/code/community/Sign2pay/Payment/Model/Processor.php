<?php

class Sign2pay_Payment_Model_Processor extends Mage_Payment_Model_Method_Abstract
{
    /**
     * Request order
     * @var Mage_Sales_Model_Order
     */
    protected $_order = null;

    /**
     * Request data
     * @var array
     */
    protected $_request = array();

    /**
     * Request data getter
     *
     * @param string $key
     * @return array|string
     */
    public function getRequestData($key = null)
    {
        if (null === $key) {
            return $this->_request;
        }
        return isset($this->_request[$key]) ? $this->_request[$key] : null;
    }

    /**
     * Get gateway data, validate and run corresponding handler
     *
     * @param array $request
     * @throws Exception
     * @todo Add gateway authentication
     * @todo Add order state validation
     */
    public function processRequest(array $request)
    {
        $this->_request = $request;

        $apiKey =  Mage::helper('sign2pay')->getSign2payApiKey();

        $orderId    = $this->getRequestData('ref_id');
        $merchantId = $this->getRequestData('merchant_id');
        $purchaseId = $this->getRequestData('purchase_id');

        $amount     = $this->getRequestData('amount');
        $status     = $this->getRequestData('status');
        $token      = $this->getRequestData('token');
        $timestamp  = $this->getRequestData('timestamp');
        $signature  = $this->getRequestData('signature');

        // Load appropriate order
        $this->_order = Mage::getModel('sales/order')->loadByIncrementId($orderId);

        if (!$this->_order->getId()) {
            throw new Exception('Requested order with id ' . $orderId . ' does not exists.');
        }

        $result = array();

        if ($this->_verifyResponse($apiKey, $token, $timestamp, $signature)) {
            if ($status == 'mandate_valid') {
                // Payment was successful, so update the order's state
                // and send order email and move to the success page
                $result['status'] = 'success';
                $result['redirect_to'] = Mage::getBaseUrl() . 'sign2pay/payment/success';
                $result['params'] = array(
                    'total'         => $amount,
                    'id'            => $orderId,
                    'purchase_id'   => $purchaseId,
                    'signature'     => true,
                    'status'        => $status,
                    'authorization' => $timestamp,
                );

                // Register the payment capture
                $this->_registerPaymentCapture();
            } else {
                // Register the payment failure
                $this->_registerPaymentDenial();
            }
        } else {
            // Register the payment failure
            $this->_registerPaymentFailure();
        }

        if (!$result) {
            // There is a problem in the response we got
            $result['status'] = 'failure';
            $result['redirect_to'] = Mage::getBaseUrl() . 'sign2pay/payment/failure';
            $result['params'] = array(
                'ref_id'    => $orderId,
                'message'   => Mage::helper('sign2pay')->__('Sorry, but we could not process your payment at this time.'),
            );
        }

        $jsonData = json_encode($result);
        Mage::app()->getResponse()->setHeader('Content-type', 'application/json');
        Mage::app()->getResponse()->setBody($jsonData);
        Mage::app()->getResponse()->sendResponse();

        return $this;
    }

    /**
     * Cancel the order
     *
     * @param Mage_Sales_Model_Order $order
     */
    public function cancel(Mage_Sales_Model_Order $order)
    {
        $order->cancel()->save();

        return $this;
    }

    /**
     * Veritfy the gateway response
     *
     * @param string $apiKey
     * @param string $token
     * @param string $timestamp
     * @param string $signature
     * @return boolean
     */
    protected function _verifyResponse($apiKey, $token, $timestamp, $signature)
    {
        return $signature === Mage::helper('sign2pay')->getSign2paySignature($apiKey, $token, $timestamp);
    }

    /**
     * Process completed payment (either full or partial)
     */
    protected function _registerPaymentCapture()
    {
        $payment = $this->_order->getPayment();
        $payment->setTransactionId($this->getRequestData('purchase_id'))
            ->setCurrencyCode('EUR')
            ->setIsTransactionClosed(0)
            ->registerCaptureNotification(
                $this->getRequestData('amount') / 100
            );
        $this->_order->setState(Mage::getStoreConfig('payment/sign2pay/complete_order_status', Mage::app()->getStore()), true);
        $this->_order->save();

        // notify customer
        $invoice = $payment->getCreatedInvoice();
        if ($invoice && !$this->_order->getEmailSent()) {
            $this->_order->sendNewOrderEmail()->addStatusHistoryComment(
                Mage::helper('sign2pay')->__('Notified customer about invoice #%s.', $invoice->getIncrementId())
            )
            ->setIsCustomerNotified(true)
            ->save();
        }

        return $this;
    }

    /**
     * Treat failed payment as order cancellation
     */
    protected function _registerPaymentFailure()
    {
        $this->_order
            ->registerCancellation(
                Mage::helper('sign2pay')->__('There was a problem with Sign2pay payment.'),
                false
            )
            ->save();

        return $this;
    }

    /**
     * Process denied payment notification
     */
    protected function _registerPaymentDenial()
    {
        $this->_order->getPayment()
            ->setTransactionId($this->getRequestData('purchase_id'))
            ->setNotificationResult(true)
            ->setIsTransactionClosed(true)
            ->registerPaymentReviewAction(Mage_Sales_Model_Order_Payment::REVIEW_ACTION_DENY, false);
        $this->_order->save();

        return $this;
    }
}
