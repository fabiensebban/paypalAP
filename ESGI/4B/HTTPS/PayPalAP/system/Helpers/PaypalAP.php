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
	                    "errorLanguage"  => "fr_FR",
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
	
	function refund($payKey)
	{
		
		//$payKey1 = "payKey=6GD48770HP9601931" ;
		$payKey1 = "transactionId=3FG328258X4984332";
		//$payKey = $payKey . "&receiverList.receiver(0).amount=100&currencyCode=EUR";
		
		return $this->_paypalSend($payKey1, "Refund"); 
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
		//var_dump($dets);die;
	
		
		$refundparams = array(
			'payKey' => 'AP-6GD48770HP9601931',
			'receiverList.receiver(0).email' => 'teamscarpa-facilitator@gmail.com',
		    'receiverList.receiver(0).amount' => '1',
		    'receiverList.receiver(1).email' => 'scarpa.zend-facilitator@gmail.com',
		    'receiverList.receiver(1).amount' => '10.54',
		    'requestEnvelope.errorLanguage' => 'fr_FR',
		    'currencyCode' => 'EUR'
			);
		
		//var_dump($this->refund($refundparams)); die;
	
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
	
	
	
	
	
	
	
	function splitPay1($returnUrl, $cancelUrl, $merchantEmail, $marketplaceEmail, $merchantAmount, $marketPlaceAmount)
	{
		
		//setting the curl parameters.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->apiUrl . "Refund");
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		//turning off the server and peer verification(TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);
		
		// Set the HTTP Headers
		curl_setopt($ch, CURLOPT_HTTPHEADER,  $this->headers);
	
	    
		// RequestEnvelope fields
		$detailLevel	= urlencode("ReturnAll");	// See DetailLevelCode in the WSDL for valid enumerations
		$errorLanguage	= urlencode("en_US");		// This should be the standard RFC 3066 language identification tag, e.g., en_US
		// NVPRequest for submitting to server
		$nvpreq = "requestEnvelope.errorLanguage=$errorLanguage&requestEnvelope.detailLevel=$detailLevel";
		//setting the nvpreq as POST FIELD to cur
		$refundparams = array(
			'payKey' => 'AP-5GS45958PF893380G',
			'receiverList.receiver(0).email' => 'merchant@email.com',
		    'receiverList.receiver(0).amount' => '100',
		    'receiverList.receiver(1).email' => 'market@place.com',
		    'receiverList.receiver(1).amount' => '10',
		    'requestEnvelope.errorLanguage' => 'fr_FR',
		    'currencyCode' => 'EUR'
			);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $refundparams);
		//getting response from server
		$response = curl_exec($ch);
	
		var_dump($response) ; die;
	}
	
	
	
	
	
    
}
?>
