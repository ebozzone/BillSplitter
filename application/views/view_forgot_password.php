<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'>

<head>	
	<title>BillSplitter | Forgot Password</title>
	<link href="<?php echo base_url(); ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<body>
	<script src="<?php echo base_url(); ?>/jquery-1.8.3.min.js"></script>
	<script src="<?php echo base_url(); ?>/bootstrap/js/bootstrap.min.js"></script>
	<!--BillSpliter banner -->
		<div style="display: table; height: 100px; #position: relative; overflow: hidden; background-color:#00297A; width:100%">
    		<div style=" #position: absolute; #top: 50%;display: table-cell; vertical-align: middle; padding-left:20px">
      			<div class="greenBorder" style=" #position: relative; #top: -50%">
        			<font color="white"><h1>BillSplitter</h1></font>
			     </div>
		    </div>
		</div>

	<div id='forgot_password_form' style="padding-left:20px;">
		<form action='<?php echo base_url();?>index.php/login/processForgotPassword' method='post' name='process'>
			<h2>Forgot Password</h2>
			<br />
			
			<label for='username'>Email Address Associated with Account</label>
			<input type='text' name='username' id='username' size='25' />
			<br />
			<?php if(! is_null($msg)) echo $msg;?>
			
		
			<input type='Submit' value='Send Password' />			
		</form>
		<a href='<?php echo site_url('login');?>'><h4>Back</h4></a>
	</div>
</body>
</html>