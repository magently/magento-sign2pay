<?php

class Sign2pay_Payment_Block_Info_Sign2pay extends Mage_Payment_Block_Info
{
    protected function _prepareSpecificInformation($transport = null)
    {
        if (null !== $this->_paymentSpecificInformation) {
            return $this->_paymentSpecificInformation;
        }
        $info = $this->getInfo();
        $transport = new Varien_Object();
        if (!empty($info['last_trans_id'])) {
            $transport->setData('Transaction URL', 'https://merchant.sign2pay.com/en/purchases/'.$info['last_trans_id']);
        }
        $transport = parent::_prepareSpecificInformation($transport);
        return $transport;
    }
}
