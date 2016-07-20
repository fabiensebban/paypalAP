<?php
/**
 * Subcription controller
 *
 * @author Scarpa Team
 * @version 3.0
 */

namespace App\Controllers;

use Core\View;
use Core\Controller;
use Helpers\PaypalAP as PaypalAP;


/**
 * Sample controller showing a construct and 2 methods and their typical usage.
 */
class Paypal extends Controller
{
    protected $code;

    /**
     * Call the parent construct
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
      POST - Call the Pay API   
      Requiered params ==> UserId, ReturnUrl, CancelUrl, MarketPlaceEmail, MerchantEmail, AmountMarketPlace, AmountMerchant
      Post params exemple ==> 
        UserId=wergv&ReturnUrl=http://www.return.com&CancelUrl=http://www.cancel.com&MarketPlaceEmail=market@place.com&MerchantEmail=merchant@email.com&AmountMarketPlace=10&AmountMerchant=100
      
    */
    
    // API Pay
    public function pay()
    {
        if($this->isValidPayPost($_POST))
        {
            $PayPalHelper = new PaypalAP($_POST['UserId']);
            $result = $PayPalHelper->splitPay($_POST['ReturnUrl'], $_POST['CancelUrl'], $_POST['MerchantEmail'], $_POST['MarketPlaceEmail'], $_POST['AmountMerchant'], $_POST['AmountMarketPlace']);
            return json_encode($result);
        }
        else 
        {
			$errorMessage = array('error' => true, 'errorMessage' => 'You did not fill all the post params');
			return json_encode($errorMessage);
        }
    }
    
    // API Refund
    public function refund()
    {
        if($this->isValidRefundPost($_POST))
        {
            $PayPalHelper = new PaypalAP($_POST['UserId']);
            $result = $PayPalHelper->refund($_POST['payKey']);
            return json_encode($result);
        }
        else 
        {
			$errorMessage = array('error' => true, 'errorMessage' => 'You did not fill all the post params');
			return json_encode($errorMessage);
        }
    }
    
    private function isValidRefundPost($postdata)
    {
        if (isset($postdata['UserId']) 
        && isset($postdata['payKey']))
            return true;

        else 
            return false;
    }
    
    private function isValidPayPost($postdata)
    {
        if (isset($postdata['UserId'])
        && isset($postdata['ReturnUrl']) 
        && isset($postdata['CancelUrl'])
        && isset($postdata['MarketPlaceEmail'])
        && isset($postdata['MerchantEmail'])
        && isset($postdata['AmountMarketPlace']) 
        && isset($postdata['AmountMerchant']))
            return true;
        
        else
            return false;
    }
}