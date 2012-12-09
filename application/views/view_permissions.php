<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title; ?></title>
	<link href="<?php echo base_url(); ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<script>

	</script>

</head>
<body>
	<script src="<?php echo base_url(); ?>/jquery-1.8.3.min.js"></script>
	<script src="<?php echo base_url(); ?>/bootstrap/js/bootstrap.min.js"></script>

	<!-- Banner -->
	<a style="display:block" href="<?php echo base_url(); ?>">
		<div style="display: table; height: 100px; #position: relative; overflow: hidden; background-color:#00297A; width:100%">
    		<div style=" #position: absolute; #top: 50%;display: table-cell; vertical-align: middle; padding-left:20px">
      			<div class="greenBorder" style=" #position: relative; #top: -50%">
        			<font color="white"><h1>BillSplitter</h1></font>
			     </div>
		    </div>
		</div>
	</a>

<div id="container" style="padding-left:20px;">
	<h1>Permissions for Collection</h1>
	<p>Here you can share this collection with friends, or remove permissions from them.
	<p>
	
	<a href='<?php echo base_url()?>index.php/site/collectionsList'>Back to List of Collections</a>

	</br></br>
	
	<table border="1" cellpadding="1" cellspacing="1" summary="Collections Table">
		<tr>
			<th>User with Access</th>
			<th>Actions</th>
		</tr>

		<?php

			foreach($permissions as $index=>$row){
				echo "<tr>";
				echo "<td>" . $row->username . "</td>";
				echo "<td>" . form_open('site/removePermission', '', array('userToRemove' => $row->username)) . form_submit('removePermission', 'Remove') .  form_close() . "</td>";
				echo "</tr>";
			}

		?>

	</table>

	<p><b>Share this Collection with friends:</b></p>
	<?php 
		echo form_open('site/addPermissions', '', array('collectionId' => $this->session->userdata('collectionId'), 'origin' => 'permissionsList'));
		echo form_input(array('name' => 'emails', 'value' => 'Enter Email(s) Separated by Commas', 'style' => 'width:300px'));
		echo form_submit('addPermissions', 'Share');
		echo form_close();
	?>
	<?php
		if($this->session->flashdata('emails_array_invalid') != NULL){
			echo "<font color=red>Invalid Emails Entered: ";
			foreach($this->session->flashdata('emails_array_invalid') as $email){
				echo $email;
				echo " ";
			}
			echo "</font>";
		}	
	?>

	</br>
	<a href='<?php echo base_url()?>index.php/site/do_logout'>Logout</a>
	
</div>

</body>
</html>