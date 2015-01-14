<?php

class Scompany_Sign2pay_PaymentController extends Mage_Core_Controller_Front_Action
{
    // The redirect action is triggered when someone places an order
    public function redirectAction()
    {
        $this->loadLayout();
        $template = Mage::getConfig()->getNode("global/page/layouts/one_column/template");

        $block = $this->getLayout()->createBlock('Mage_Core_Block_Template', 'sign2pay', array('template' => 'sign2pay/redirect.phtml'));
        $this->getLayout()->getBlock('content')->setTemplate($template);
        $this->getLayout()->getBlock('content')->append($block);
        $this->renderLayout();
    }

    // The response action is triggered when your gateway sends back a response after processing the customer's payment
    public function responseAction()
    {

      Mage::log('responseAction: ', null, 'sign2pay.log');
      if ($this->getRequest()->isPost()) {

          /*
          /* Your gateway's code to make sure the reponse you
          /* just got is from the gatway and not from some weirdo.
          /* This generally has some checksum or other checks,
          /* and is provided by the gateway.
          /* For now, we assume that the gateway's response is valid
          */

          //TODO:implement getApiKey()
          $apiKey =  Mage::getStoreConfig('payment/sign2pay/api_token', Mage::app()->getStore());
          $orderId = $_REQUEST["ref_id"];
          $merchantId = $_REQUEST["merchant_id"];
          $purchaseId = $_REQUEST["purchase_id"];
          $refId = $_REQUEST["ref_id"];
          $amount = $_REQUEST["amount"];
          $status = $_REQUEST["status"];
          $token = $_REQUEST["token"];
          $timestamp = $_REQUEST["timestamp"];
          $signature = $_REQUEST["signature"];

          $validated = $this->verifyResponse($apiKey, $token, $timestamp, $signature);
          Mage::log('response validated: ' . $validated, null, 'sign2pay.log');

          if ($validated && $status == "mandate_valid") {
            $redirect = Mage::getBaseUrl() . "checkout/onepage/success";
            // Payment was successful, so update the order's state, send order email and move to the success page
            $order = Mage::getModel('sales/order');
            $order->loadByIncrementId($orderId);
            $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true, 'Sign2pay has authorized the payment. with ID = ' . $purchaseId);

            $order->sendNewOrderEmail();
            $order->setEmailSent(true);

            $order->save();

            Mage::getSingleton('checkout/session')->unsQuoteId();
            $arr = array('status' => "success",
                'redirect_to' => $redirect,
                'params' => array(
                    "total" => $amount,
                    "id" => $refId,
                    "purchase_id" => $purchaseId,
                    "signature" => true,
                    "status" => $status,
                    "authorization" => $timestamp
                )
            );

          } else {
              // There is a problem in the response we got
              $redirect = Mage::getBaseUrl() . "checkout/onepage/failure";
              $this->cancelOrder($orderId);

              $arr = array('status' => "failure",
                  'redirect_to' => $redirect,
                  'params' => array(
                      "ref_id" => $refId,
                      "message" => "Sorry, but we could not process your payment at this time. Your Order is still Pending.",
                  )
              );
          }

          $anwser = json_encode($arr);
          $anwser = str_replace("\\/", "/", $anwser);
          Mage::log('responseAction json: ' . $anwser, null, 'sign2pay.log');
          echo $anwser;
      }
    }

    // The cancel action is triggered when the popup is closed.
    public function cancelAction()
    {
      Mage::log('cancelAction who is calling this!  ', null, 'sign2pay.log');
      $orderId = Mage::getSingleton('checkout/session')->getLastRealOrderId();
      $order = Mage::getSingleton('sales/order')->loadByIncrementId($orderId);

      if($order->canCancel()) {
        $order->cancel()->save();
      }

      $cart = Mage::getModel('checkout/cart');
      $cart->init();
      foreach($order->getAllVisibleItems() as $item):
        $cart->addProduct($item->getProductId(), $item->getQtyOrdered());
      endforeach;
      $cart->save();

      Mage::getSingleton('checkout/session')->setCartWasUpdated(true);
      Mage::getSingleton('checkout/session')->addError("You've cancelled the Sign2Pay screen.");
      Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('checkout/cart'))->sendResponse();
    }

    private function cancelOrder($orderId)
    {
      Mage::log('canelAction order: ' . $orderId, null, 'sign2pay.log');
      $order = Mage::getModel('sales/order');
      $order->loadByIncrementId($orderId);
      if ($order->getId()) {
          $order->cancel()->setState(Mage_Sales_Model_Order::STATE_CANCELED, true, 'Sign2pay has declined the payment.')->save();
      }
    }

    function verifyResponse($api_key, $token, $timestamp, $signature)
    {
        return $signature === hash_hmac(
            "sha256",
            $timestamp . $token,
            $api_key
        );
    }


}
