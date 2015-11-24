<?php

class Sign2pay_Payment_Model_Observer
{
    /**
     * Modifies block html.
     *
     * @event core_block_abstract_to_html_after
     */
    public function afterBlockHtml($observer)
    {
        $block = $observer->getBlock();
        $transport = $observer->getTransport();

        if ($block instanceof Mage_Checkout_Block_Onepage_Shipping_Method_Available) {
            $payment = Mage::getModel('sign2pay/sign2pay');

            if (method_exists($payment, 'isApplicableToQuote')) {
                try {
                    if ($payment->isApplicableToQuote(
                            $block->getQuote(),
                            Mage_Payment_Model_Method_Abstract::CHECK_USE_FOR_COUNTRY
                            | Mage_Payment_Model_Method_Abstract::CHECK_USE_FOR_CURRENCY
                            | Mage_Payment_Model_Method_Abstract::CHECK_ORDER_TOTAL_MIN_MAX
                            | Mage_Payment_Model_Method_Abstract::CHECK_ZERO_TOTAL
                        ))
                    {
                        $this->_addUpdate($transport);
                    }
                } catch (Exception $e) {
                    // Add anyway
                    $this->_addUpdate($transport);
                }
            } else {
                // Add anyway
                $this->_addUpdate($transport);
            }
        }
    }

    /**
     * Add riska assessment trigger to transport
     */
    protected function _addUpdate($transport)
    {
        $html = '<script type="text/javascript">if (typeof updateSign2pay === \'function\') updateSign2pay();</script>';
        $transport->setHtml($transport->getHtml() . $html);
    }
}
