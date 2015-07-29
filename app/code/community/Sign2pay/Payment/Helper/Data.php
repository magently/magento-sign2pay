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
     * Attach payment scripts.
     */
    public function attachPaymentScripts(array $additional = array())
    {
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

}
