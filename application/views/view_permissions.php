<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title; ?></title>

	<script>

	</script>

</head>
<body>

<div id="container">
	<h1>Permissions for Collection: [TO COME]</h1>
	
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