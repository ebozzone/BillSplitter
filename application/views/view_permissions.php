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
	
	</br>
	
	<table border="1" cellpadding="1" cellspacing="1" summary="Collections Table">
		<tr>
			<th>User with Access</th>
			<th>Actions</th>
		</tr>

		<?php

			foreach($permissions as $index=>$row){
				echo "<tr>";
				echo "<td>" . echo $row->username . "</td>";
				echo "<td>" . form_open('site/removePermission', '', array('username' => $row->username)) . form_submit('removePermission', 'Remove') .  form_close() . "</td>";
				echo "</tr>";
			}

		?>

	</table>
	</br>
	<a href='<?php echo base_url()?>index.php/site/do_logout'>Logout</a>
	
</div>

</body>
</html>