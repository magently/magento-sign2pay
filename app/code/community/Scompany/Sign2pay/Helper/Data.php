<?php

class Scompany_Sign2pay_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * Retrive sign2pay options.
     *
     * @return array
     */
    public function getSign2PayOptions()
    {
        return array(
            'merchantId' => Mage::getStoreConfig('payment/sign2pay/merchant_id',Mage::app()->getStore()),
            'token' => Mage::getStoreConfig('payment/sign2pay/application_token',Mage::app()->getStore()),
        );
    }

    /**
     * Attach payment scripts.
     */
    public function attachPaymentScripts(array $additional = array())
    {
        Mage::app()->getLayout()->getBlock('head')->addJs('sign2pay/jquery.min.js');
        Mage::app()->getLayout()->getBlock('head')->addJs('sign2pay/payment.js');

        Mage::app()->getLayout()->getBlock('head')->append(
            Mage::app()->getLayout()->createBlock('core/text')->setText(
                '<script type="text/javascript" src="https://sign2pay.com/merchant.js"></script>'
            )
        );

        $options = $additional + $this->getSign2PayOptions();
        $options['baseUrl'] = Mage::getBaseUrl();

        $script = 'window.s2pOptions = ' . json_encode($options) . ';';
        Mage::app()->getLayout()->getBlock('head')->append(
            Mage::app()->getLayout()->createBlock('core/text')->setText(
                '<script type="text/javascript">' . $script . '</script>'
            )
        );
    }

}
