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
				echo "<td> <a href='" . base_url() . "index.php/site/dashboardLink?collectionId=" . $row->collectionId . "'>Link to Collection ID # " . $row->collectionId . "</a> </td>";
				echo "<td> Some Date </td>";
				echo "<td> Your Friends </td>";
				echo "<td>" . form_open('site/removePermission', '', array('collectionId' => $row->collectionId)) . form_submit('removeRow', 'Remove') .  form_close() . "</td>";
				echo "</tr>";
			}

		?>

	</table>
	</br>
	<a href='<?php echo base_url()?>index.php/site/do_logout'>Logout</a>
	
</div>

</body>
</html>