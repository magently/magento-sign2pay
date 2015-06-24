<?php
class Scompany_Sign2pay_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {

    }

    public function optionDetailAction()
    {
        $productId = $_REQUEST['id'];
        $product = Mage::getModel('catalog/product')->load($productId);
        $productId = $product->getId();
        $productsku = $product->getSku();
        $productname = $product->getName();
        $price =  Mage::helper('core')->currency($product->getFinalPrice(),true,false);

        $arraygroup = array("price"=>$productId, "sku"=>$productsku, "name"=>$productname, "price"=>$price);
        echo json_encode($arraygroup);
    }

    public function totalPurchaseAmountAction(){
        Mage::log('totalPurchaseAmountAction: ' , null, 'sign2pay.log');
        echo  Mage::getSingleton('checkout/session')->getQuote()->getGrandTotal();
    }

}
