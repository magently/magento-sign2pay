<?php
include '/var/www/html/ChromePhp.php';

class Sign2pay_Payment_Model_Sign2pay extends Mage_Payment_Model_Method_Abstract
{
    protected $_code = 'sign2pay';
    protected $_formBlockType = 'sign2pay/form_sign2pay';
    protected $_infoBlockType = 'sign2pay/info_sign2pay';
    protected $_isInitializeNeeded      = true;
    protected $_canUseInternal          = true;
    protected $_canUseForMultishipping  = false;

    public function getOrderPlaceRedirectUrl()
    {
        //return Mage::getUrl('sign2pay/payment/redirect', array('_secure' => true));
        
        $client_id = 'ace647b5bba31f9616ea30b107ae0a4a';
        $redirect_uri = 'https://rkky1b9r.magently.com/sign2pay/callback'; //Mage::getUrl('sign2pay/payment/response', array('_secure' => true));
        $scope = 'authenticate';
        $state = 'test'//Mage::getSingleton("core/session")->getEncryptedSessionId();
        $response_type = 'code';
        $device_uid = 'test';

        $query = http_build_query(array(
                'client_id' => $client_id,
                'redirect_uri' => $redirect_uri,
                'scope' => $scope,
                'state' => $state,
                'response_type' => $response_type,
                'device_uid' => $device_uid
                )
            );
        /*$redirect = http_build_url('https://app.sign2pay.com/', array(
                'path' => 'oauth/authorize',
                'query' => $query
            ), HTTP_URL_JOIN_QUERY
        );*/
        return 'https://app.sign2pay.com/oauth/authorize/?'.$query;
        //ChromePhp::log($redirect);

    }

    /**
     * Check method for processing with base currency
     *
     * @param string $currencyCode
     * @return boolean
     */
    public function canUseForCurrency($currencyCode)
    {
        return $currencyCode == 'EUR';
    }
}
