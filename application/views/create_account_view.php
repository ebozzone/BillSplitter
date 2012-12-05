<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'>

<head>	
	<title>BillSplitter | Create Account</title>
</head>
<body>
	<div id='create_account_form'>
		<form action='<?php echo base_url();?>index.php/login/processCreateAccount' method='post' name='process'>
			<h2>Create Account</h2>
			<br />
			<?php if(! is_null($msg)) echo $msg;?>			
			<label for='username'>Email Address</label>
			<input type='text' name='username' id='username' size='25' /><br />
		
			<label for='password'>Password</label>
			<input type='password' name='password' id='password' size='25' /><br />

			<label for='confirmation'>Confirmation</label>
			<input type='password' name='confirmation' id='confirmation' = size='25' /><br />							
		
			<input type='Submit' value='Login' />			
		</form>
		<a href='<?php echo site_url('login');?>'>Back</a>
	</div>
</body>
</html>