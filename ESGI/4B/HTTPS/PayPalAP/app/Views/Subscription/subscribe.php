<section id="loginform" class="outer-wrapper">


<div class="alert alert-success">
    CONGRATULATION ! You have create your account. 
</div>


<div class="inner-wrapper">
	<div class="container">
	  <div class="row">
	    <div class="col-sm-8 col-sm-offset-2">
	      <h2 class="text-center">Use de folowing code for your Rails project</h2>
	      
	      <h3>Create a payment order</h3>
	      <pre>
    	      <code>
    	          response = Net::HTTP.post_form(uri,
                                     'UserId'            => <?php echo $data['userId']; ?>,
                                     'ReturnUrl'         => [RETUNR URL],
                                     'CancelUrl'         => [CANCEL URL],
                                     'MarketPlaceEmail'  => [MARKETPALCE EMAIL],
                                     'MerchantEmail'     => [MERCHANT EMAIL],
                                     'AmountMarketPlace' => [AMOUNT TO MARKETPALCE - STRING],
                                     'AmountMerchant'    => [AMOUNT TO MERCHANT - STRING]

			      scarPayResponseDecode = JSON.parse response.body
			      scarPayResponse = Hashie::Mash.new paypalResponseDecode
    	      </code>
	      </pre>
	      <h3>Response (JSON)</h3>
	      <pre>
    	      <code>
    	          	{
					  "error": (boolean),
					  "errorMessage": (if error),
					  "url": ((if !error) Pay URL),
					  "payKey": (Paypal key)
					}
    	      </code>
	      </pre>
	     </div>
	   </div>
	 </div>
</div>

<script type="text/javascript">
	
$(document).ready(function() {
  $('pre code').each(function(i, block) {
    hljs.highlightBlock(block);
  });
});

</script>