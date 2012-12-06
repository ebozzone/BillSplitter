<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'>

<head>	
	<title>BillSplitter | Forgot Password</title>
</head>
<body>
	<div id='forgot_password_form'>
		<form action='<?php echo base_url();?>index.php/login/processForgotPassword' method='post' name='process'>
			<h2>Forgot Password</h2>
			<br />
			
			<label for='username'>Email Address Associated with Account</label>
			<input type='text' name='username' id='username' size='25' />
			<br />
			<?php if(! is_null($msg)) echo $msg;?>
			
		
			<input type='Submit' value='Send Password' />			
		</form>
		<a href='<?php echo site_url('login');?>'>Back</a>
	</div>
</body>
</html>