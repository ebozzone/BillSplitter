<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'>

<head>	
	<title>BillSplitter Login Screen | Welcome </title>
	<link href="<?php echo base_url(); ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<style type="text/css">
      body {
        padding-top: 0px;
        padding-bottom: 20px;
      }

      /* Custom container */
      .container-narrow {
        margin: 0 auto;
        max-width: 800px;
      }
      .container-narrow > hr {
        margin: 30px 0;
      }

      /* Supporting marketing content */
      .marketing {
        margin: 60px 0;
      }
      .marketing p + h4 {
        margin-top: 28px;
      }
    </style>
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

	<div class="container-narrow">

		<div class="span6" style="display: table; height: 400px; #position: relative; overflow: hidden;">
    		<div style=" #position: absolute; #top: 50%;display: table-cell; vertical-align: middle;">
      			<div class="greenBorder" style=" #position: relative; #top: -50%;">
        			<center>
        				<h1>The easiest way </br>to split bills.</h1>
        				</br></br>
        				<a style="#position: relative;" class="btn btn-large btn-success" href='<?php echo site_url('login/createCollectionNoLogin');?>'>Start a New Collection</a>
        				</br> <font color="grey"><i>No login required!</i></font>
        			</center>
			     
			     </div>
		    </div>
		</div>

		<div class="span3" style="display: table; height: 400px; #position: relative; overflow: hidden;">
    		<div style=" #position: absolute; #top: 50%;display: table-cell; vertical-align: middle;">
      			<div class="greenBorder" style=" #position: relative; #top: -50%">
        			<form action='<?php echo base_url();?>index.php/login/process' method='post' name='process'>
				<h4>Or, sign in:</h4>
				<br />
				<?php if(! is_null($msg)) echo $msg;?>			
				<label for='username'>Email Address</label>
				<input type='text' name='username' id='username' size='25' /><br />
			
				<label for='password'>Password</label>
				<input type='password' name='password' id='password' size='25' /><br />							
			
				<input type='Submit' value='Login' />			
			</form>
			<a href='<?php echo site_url('login/forgotPassword');?>'>Forgot Password</a>
			</br>
			<a href='<?php echo site_url('login/createAccount');?>'>Create Account</a>
			     </div>
		    </div>
		</div>
	</div>
</body>
</html>