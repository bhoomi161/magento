<?php
class Ccc_ShippingMethod_Model_Carrier
    extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface
{
    protected $_code = 'shippingmethod';

    public function collectRates(
        Mage_Shipping_Model_Rate_Request $request
    )
    {
        $result =  Mage::getModel('shipping/rate_result');
        $result->append($this->_getExpressShippingRate());  
        return $result;
    }

    public function getAllowedMethods()
    {
        return array(
            'express' => 'Express',
        );   
    }
    
    protected function _getExpressShippingRate()
    {
        $rate = Mage::getModel('shipping/rate_result_method');
        /* @var $rate Mage_Shipping_Model_Rate_Result_Method */
        $rate->setCarrier($this->_code);
        $rate->setCarrierTitle($this->getConfigData('title'));
        $rate->setMethod('express');
        $rate->setMethodTitle('Express (Next day)');
        $rate->setPrice(12.99);
        $rate->setCost(0);
        return $rate;
    }

}
