<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'>

<head>	
	<title>BillSplitter | Create Account</title>
	<link href="<?php echo base_url(); ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<body>
	<!--BillSpliter banner -->
		<div style="display: table; height: 100px; #position: relative; overflow: hidden; background-color:#00297A; width:100%">
    		<div style=" #position: absolute; #top: 50%;display: table-cell; vertical-align: middle; padding-left:20px">
      			<div class="greenBorder" style=" #position: relative; #top: -50%">
        			<font color="white"><h1>BillSplitter</h1></font>
			     </div>
		    </div>
		</div>
	<script src="<?php echo base_url(); ?>/jquery-1.8.3.min.js"></script>
	<script src="<?php echo base_url(); ?>/bootstrap/js/bootstrap.min.js"></script>
	<div id='create_account_form' style="padding-left:20px;">
		<form action='<?php echo base_url();?>index.php/login/processCreateAccount' method='post' name='process'>
			<h3>Create an account</h3>
			<br />
			<?php if(! is_null($msg)) echo $msg;?>			
			<label for='username'>Email Address</label>
			<input type='text' name='username' id='username' size='25' /><br />

			<label for='fname'>First Name</label>
			<input type='text' name='fname' id='fname' size='25' /><br />
		
			<label for='password'>Password</label>
			<input type='password' name='password' id='password' size='25' /><br />

			<label for='confirmation'>Confirmation</label>
			<input type='password' name='confirmation' id='confirmation' = size='25' /><br />							
		
			<input type='Submit' value='Create Account' />			
		</form>
		<a href='<?php echo site_url('login');?>'>Login With Existing Account</a>
	</div>
</body>
</html>