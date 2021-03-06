<div class="page-header">
	<h1>ScarPay - The easy paypal adaptive payment </h1>
</div>

<?php if (isset( $data['errors']) && $data['isErrors']) {?>
<div class="alert alert-danger">
	<?php 
		foreach( $data['errors'] as $error){
			echo $error . "</br>";
	}
	?>
</div>
<?php } ?>

<section id="loginform" class="outer-wrapper">

<div class="inner-wrapper">
	<div class="container">
	  <div class="row">
	    <div class="col-sm-6 col-sm-offset-3">
	      <h2 class="text-center">Fill your information</h2>
	      
	      <form method="post" action="subscribe">
	      
			  <div class="form-group">
			    <label for="userID">Scarpay User identifier</label>
			    <input type="text" class="form-control" id="userID" name="userID" placeholder="Enter user ID" value="<?php if(isset($data['post']['userID'])) echo $data['post']['userID'];?>">
			  </div>
			  <div class="form-group">
			    <label for="appID">Paypal - Application ID</label>
			    <input type="text" class="form-control" id="appID" name="appID" placeholder="APP ID" value="<?php if(isset($data['post']['appID'])) echo $data['post']['appID'];?>">
			  </div>
			  <div class="form-group">
			    <label for="appPass">Paypal - API Password</label>
			    <input type="text" class="form-control" id="apiPass" name="apiPass" placeholder="API Password" value="<?php if(isset($data['post']['apiPass'])) echo $data['post']['apiPass'];?>">
			  </div>
			  <div class="form-group">
			    <label for="appUser">Paypal - API User</label>
			    <input type="text" class="form-control" id="apiUser" name="apiUser" placeholder="API User" value="<?php if(isset($data['post']['apiUser'])) echo $data['post']['apiUser'];?>">
			  </div>
			  <div class="form-group">
			    <label for="appSig">Paypal - API Signature</label>
			    <input type="text" class="form-control" id="apiSig" name="apiSig" placeholder="API Signature" value="<?php if(isset($data['post']['apiSig'])) echo $data['post']['apiSig'];?>">
			  </div>
			  
			  <input type="submit" name="submit" class="btn btn-default" value="Submit" />
			  
			</form>
	    </div>
	  </div>
	</div>
</div>

</section>