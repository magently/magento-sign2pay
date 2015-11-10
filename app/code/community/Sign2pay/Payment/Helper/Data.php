<?php

class Sign2pay_Payment_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * Retrive Sign2Pay merchant id.
     *
     * @return string
     */
    public function getSign2payMerchantId()
    {
        return Mage::getStoreConfig('payment/sign2pay/merchant_id',Mage::app()->getStore());
    }

    /**
     * Retrive Sign2Pay token.
     *
     * @return string
     */
    public function getSign2payToken()
    {
        return Mage::getStoreConfig('payment/sign2pay/application_token',Mage::app()->getStore());
    }

    /**
     * Retrive Sign2Pay api key.
     *
     * @return string
     */
    public function getSign2payApiKey()
    {
        return Mage::getStoreConfig('payment/sign2pay/api_token',Mage::app()->getStore());
    }

    /**
     * Retrive Sign2Pay Client ID.
     *
     * @return string
     */
    public function getSign2payClientId()
    {
        return Mage::getStoreConfig('payment/sign2pay/client_id',Mage::app()->getStore());
    }

    /**
     * Retrive Sign2Pay Client Secret token.
     *
     * @return string
     */
    public function getSign2payClientSecret()
    {
        return Mage::getStoreConfig('payment/sign2pay/client_secret',Mage::app()->getStore());
    }

    /**
     * Retrive sign2pay options.
     *
     * @return array
     */
    public function getSign2PayOptions()
    {
        return array(
            'merchantId' => $this->getSign2payMerchantId(),
            'token' => $this->getSign2payToken(),
        );
    }

    /**
     * Retrive sign2pay options.
     *
     * @return array
     */
    public function getSign2PaySignature($apiKey, $token, $timestamp)
    {
        return hash_hmac(
            "sha256",
            $timestamp . $token,
            $apiKey
        );
    }

    /**
     * Build and return url to module's response action.
     *
     * @return array
     */
    public function getRedirectUri()
    {
        $redirect_uri = Mage::getUrl('sign2pay/payment/response', array('_secure' => true));
        $redirect_uri = preg_replace('/index.php\/sign2pay/', 'sign2pay', $redirect_uri);
        return rtrim($redirect_uri,"/");
    }

    /**
     * Attach payment scripts.
     */
    public function attachPaymentScripts(array $additional = array())
    {
        /**
         * @todo Properly remove/update this functionality
         */
        return;

        Mage::app()->getLayout()->getBlock('head')->addJs('sign2pay/jquery.min.js');
        Mage::app()->getLayout()->getBlock('head')->addJs('sign2pay/payment.js');

        $options = $additional + $this->getSign2PayOptions();
        $options['baseUrl'] = Mage::getBaseUrl();

        $script = 'window.s2pOptions = ' . json_encode($options) . ';';
        Mage::app()->getLayout()->getBlock('head')->append(
            Mage::app()->getLayout()->createBlock('core/text')->setText(
                '<script type="text/javascript">' . $script . '</script>'
            )
        );
    }

    /**
     * Attach to session and return user sign2pay checkout session hash.
     *
     * @return string
     */
    private function userStateHash(){
        $hash = Mage::helper('core')->getRandomString(16);
        Mage::getSingleton('checkout/session')->setSign2PayUserHash($hash);
        return $hash;
    }

    /**
     * Attach to session and return sign2pay checkout session hash.
     *
     * @return string
     */
    private function sign2PayCheckoutHash($id){
        $hash = Mage::helper('core')->getRandomString(16);
        Mage::getSingleton('checkout/session')->setSign2PayCheckoutHash($hash);
        return $hash;
    }

    /**
     * Set status on order
     *
     * @param Mage_Sales_Model_Order $order
     * @param string $status_code
     */
    public function setStatusOnOrder($order, $status_code)
    {
        $collection = Mage::getResourceModel('sales/order_status_collection');
        $collection->joinStates();
        $collection->getSelect()
            ->where('main_table.status=?', $status_code)
            ->limit(1);

        $status = $collection->fetchItem()->getData();

        $order->setState($status['state'], $status['status']);
    }

    /**
     * Prepare and return initial Sign2Pay request
     * @todo device unical id
     *
     * @return string
     */
    public function getSign2PayInitialRequest(){
        $quote = Mage::getSingleton('checkout/session')->getQuote();
        
        Mage::log($quote->getData());
        $amount = $quote['grand_total']*100;
        $amount = preg_replace('/[^0-9]/', '', $amount);
        $ref_id = $this->sign2PayCheckoutHash($quote['reserved_order_id']);

        $billing = $quote->getBillingAddress();

        $baseUrl = 'https://app.sign2pay.com/oauth/authorize';
        $client_id = $this->getSign2payClientId();
        $redirect_uri = $this->getRedirectUri();

        $scope = 'payment';
        $state = $this->userStateHash();
        $response_type = 'code';
        $device_uid = 'test';

        $query = http_build_query(array(
            'client_id' => $client_id,
            'redirect_uri' => $redirect_uri,
            'scope' => $scope,
            'amount' => $amount,
            'state' => $state,
            'ref_id' => $ref_id,
            'response_type' => $response_type,
            'device_uid' => $device_uid,            
            'user_params[identifier]' => $billing['email'],
            'user_params[first_name]' => $billing['firstname'],
            'user_params[last_name]' => $billing['lastname'],
            'user_params[address]' => $billing['street'],
            'user_params[city]' => $billing['city'],
            'user_params[postal_code]' => $billing['postcode']
            )
        );
        return $baseUrl.'?'.$query;

    }

}
