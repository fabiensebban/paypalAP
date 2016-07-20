<?php

namespace Helpers;

class PaypalAP
{
	var $apiUrl    = "https://svcs.sandbox.paypal.com/AdaptivePayments/";
	var $paypalUrl = "https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_ap-payment&paykey=";

    /**
     * Call the parent construct
     */
    public function __construct($userId)
    {
    	$this->_users = new \App\Models\User();
    	$user = $this->_users->getUser($userId);
    	
    	if ($user == null)
    	{
    		$this->_users = false;
    	}
    	else
    	{
	    	$user = $user[0];
	    	
	        $this->headers = array(
				"X-PAYPAL-SECURITY-USERID: " . $user->apiUser,
				"X-PAYPAL-SECURITY-PASSWORD: " . $user->apiPass,
				"X-PAYPAL-SECURITY-SIGNATURE: " . $user->apiSig,
				"X-PAYPAL-REQUEST-DATA-FORMAT: JSON",
				"X-PAYPAL-RESPONSE-DATA-FORMAT: JSON",
				"X-PAYPAL-APPLICATION-ID: " . $user->appID
			);
	    
	        $this->envelope = array(				
	                    "errorLanguage"  => "en_US",
	    				"detailLevel"    => "ReturnAll"
					);
    	}
    }
    
	// wrapper for getting payment details
	function getPaymentOptions($paykey) {
	    
        $packet = array(
            "requestEnvelope" => $this->envelope,
            "payKey" => $paykey
        );
        
        return $this->_paypalSend($packet, "GetPaymentOptions");
	}

	function setPaymentOptions($payKey) {
		return array(
		    "requestEnvelope" => array(
				"errorLanguage"  => "fr_FR",
				"detailLevel"    => "ReturnAll",
			),
			"payKey" => $payKey,
			/*	
			"receiverOptions" => array(
			    array(
			        "receiver" => array( "email" => $marketplaceEmail),
			        "invoiceData" => array(
			                "item" => array(
			                    array(
			                        "name" => "MarketPlace Service",
			                        "price" => "2.00",
			                        "identifier" => "MS1"
			                    )
			                )
			            )
			        )
			    array(
			        array(
			        "receiver" => array( "email" => $merchantEmail),
			        "invoiceData" => array(
			                "item" => array(
			                    array(
			                        "name" => "Product 1 ",
			                        "price" => "2.00",
			                        "identifier" => "P1"
			                    )
			                )
			            )
			        )
			    ),
			)
			*/
		);
	}

	// create PAY call to paypal
	function createPayRequest($createPacket) {
		return $this->_paypalSend($createPacket, "Pay");
	}
	

	/*
	Command Line: 
	
	curl https://svcs.sandbox.paypal.com/AdaptivePayments/Refund \
	  -s \
	  --insecure \
	  -H "X-PAYPAL-SECURITY-USERID: teamscarpa-facilitator_api1.gmail.com" \
	  -H "X-PAYPAL-SECURITY-PASSWORD: X9C6GA6JFM85GA8H" \
	  -H "X-PAYPAL-SECURITY-SIGNATURE: AFcWxV21C7fd0v3bYYYRCpSSRl31AUdJlJ7WKvwwCfr5Km0licmZGSGT" \
	  -H "X-PAYPAL-REQUEST-DATA-FORMAT: NV" \
	  -H "X-PAYPAL-RESPONSE-DATA-FORMAT: NV" \
	  -H "X-PAYPAL-APPLICATION-ID: APP-80W284485P519543T "   \
	  -d requestEnvelope.errorLanguage=en_US \
	  -d payKey=AP-5H693988ER680773J
	*/
	
	
	function refund($payKey)
	{
		$payKey = "payKey=" . $payKey;
		//$refundParams = array($payKey, "requestEnvelope.errorLanguage=en_US");
		
		return $this->_paypalSend($payKey, "Refund"); 
	}

	// curl wrapper for sending things to paypal
	function _paypalSend($data, $call) {

		ob_start();
		$out = fopen('output', 'w');

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->apiUrl.$call);
		//curl_setopt($ch, CURLOPT_SSLVERSION, 6);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		
		if ($call == "Refund")
		{
			curl_setopt($ch, CURLOPT_POSTFIELDS, "requestEnvelope.errorLanguage=en_US");	
		}
		
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);

		// For debuguing
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_STDERR, $out);

		$payPalResponse = curl_exec($ch);

        // For debuguing
		fclose($out);
		$debug = ob_get_clean();
		
		return json_decode($payPalResponse, TRUE);
	}


/*
	Commande Line: 
	
	curl https://svcs.sandbox.paypal.com/AdaptivePayments/Pay \
	-s \
   --insecure \
   -H "X-PAYPAL-SECURITY-USERID: teamscarpa-facilitator_api1.gmail.com" \
   -H "X-PAYPAL-SECURITY-PASSWORD: X9C6GA6JFM85GA8H" \
   -H "X-PAYPAL-SECURITY-SIGNATURE: AFcWxV21C7fd0v3bYYYRCpSSRl31AUdJlJ7WKvwwCfr5Km0licmZGSGT" \
   -H "X-PAYPAL-REQUEST-DATA-FORMAT: JSON" \
   -H "X-PAYPAL-RESPONSE-DATA-FORMAT: JSON" \
   -H "X-PAYPAL-APPLICATION-ID: APP-80W284485P519543T" \
   -d '{
   "actionType":"PAY",
   "currencyCode":"USD",
   "receiverList":{
     "receiver":[
       {
         "amount":"1.00",
         "email":"adama_sakho-facilitator@hotmail.com"
       }
     ]
   },
   "returnUrl":"http://www.example.com/success.html",
   "cancelUrl":"http://www.example.com/failure.html",
   "requestEnvelope":{
     "errorLanguage":"en_US",
     "detailLevel":"ReturnAll"
   }
 }'
*/
	function splitPay($returnUrl, $cancelUrl, $merchantEmail, $marketplaceEmail, $merchantAmount, $marketPlaceAmount) 
	{
		if(!$this->_users)
		{
			return array('error'	=> true, 
						 'errorMessage'	=> "No UserId found");
		}
		
		// create the pay request
		$createPacket = array(
			"actionType"   => "PAY",
			"currencyCode" => "EUR",
			"receiverList" => array(
				"receiver"    => array(
					array(
						"amount" => $marketPlaceAmount,
						"email"  => $marketplaceEmail,
					),
					array(
						"amount" => $merchantAmount,
						"email"  => $merchantEmail,
					),
				),
			),
			"returnUrl"       => $returnUrl,
			"cancelUrl"       => $cancelUrl,
			"requestEnvelope" => $this->envelope
		);
		
		$response = $this->createPayRequest($createPacket);

		//Verify if errors
		if ($response['responseEnvelope']['ack'] == "Failure")
		{
			$errorMessage = $response['error'][0];
			return array('error' => true, 'errorMessage' => $errorMessage['message']);
		}
		
		$payKey = $response['payKey'];
		
		/*
		$detailsPacket = $this->setPaymentOptions("AP-6GD48770HP9601931");
		$response = $this->_paypalSend($detailsPacket, "SetPaymentOptions");
		$dets = $this->getPaymentOptions("AP-6GD48770HP9601931");
		*/
	
		/*
		$refundparams = array(
			'payKey' => 'AP-6GD48770HP9601931',
			'receiverList.receiver(0).email' => 'teamscarpa-facilitator@gmail.com',
		    'receiverList.receiver(0).amount' => '1',
		    'receiverList.receiver(1).email' => 'scarpa.zend-facilitator@gmail.com',
		    'receiverList.receiver(1).amount' => '10.54',
		    'requestEnvelope.errorLanguage' => 'fr_FR',
		    'currencyCode' => 'EUR'
			);
		*/
	
		return array('error'		=> false, 
					 'url'			=> $this->paypalUrl . $payKey,
					 'payKey'		=> $payKey/*,
					 'trackingId'	=> $trackingId*/);
		
		// SET PAYMENT DETAILS 
		//$detailsPacket = $this->setPaymentOptions($payKey);
		//$response = $this->_paypalSend($detailsPacket, "SetPaymentOptions");
		
		//GET DETAILS 
		//$dets = $this->getPaymentOptions($payKey);
		
	}
	
}
?>
