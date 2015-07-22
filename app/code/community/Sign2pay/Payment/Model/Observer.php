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
            if (Mage::getModel('sign2pay/sign2pay')->isApplicableToQuote(
                    $block->getQuote(),
                    Mage_Payment_Model_Method_Abstract::CHECK_USE_FOR_COUNTRY
                    | Mage_Payment_Model_Method_Abstract::CHECK_USE_FOR_CURRENCY
                    | Mage_Payment_Model_Method_Abstract::CHECK_ORDER_TOTAL_MIN_MAX
                    | Mage_Payment_Model_Method_Abstract::CHECK_ZERO_TOTAL
                ))
            {
                $html = '<script type="text/javascript">initializeRiskAssessment();</script>';
                $transport->setHtml($transport->getHtml() . $html);
            }
        }
    }
}
