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
                        $this->_addInitializeRiskAssessment($transport);
                    }
                } catch (Exception $e) {
                    // Add anyway
                    $this->_addInitializeRiskAssessment($transport);
                }
            } else {
                // Add anyway
                $this->_addInitializeRiskAssessment($transport);
            }
        }
    }

    /**
     * Add riska assessment trigger to transport
     */
    protected function _addInitializeRiskAssessment($transport)
    {
        $html = '<script type="text/javascript">if (typeof initializeRiskAssessment === \'function\') initializeRiskAssessment();</script>';
        $transport->setHtml($transport->getHtml() . $html);
    }
}
