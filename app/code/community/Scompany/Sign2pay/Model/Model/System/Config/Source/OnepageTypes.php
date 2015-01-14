<?php

class Scompany_Sign2pay_Model_Model_System_Config_Source_OnepageTypes
{
    public function toOptionArray()
    {
        return array(
            array(
                'value' => 'standard',
                'label' => 'standard',
            ),
            array(
                'value' => 'mage_world',
                'label' => 'Mage World',
            ),
        );
    }
}