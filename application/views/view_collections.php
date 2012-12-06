<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title; ?></title>

	<script>

	</script>

</head>
<body>

<div id="container">
	<h1><?php echo $this->session->userdata('username') ?>'s List of Collections:</h1>
	
	</br>
	
	<table border="1" cellpadding="1" cellspacing="1" summary="Collections Table">
		<tr>
			<th>Name of Collection of Bills</th>
			<th>Date Created</th>
			<th>People Splitting the Bill</th>
			<th>Contributors</th>
			<th>Private?</th>
			<th>Actions</th>
		</tr>

		<?php

			foreach($collections as $index=>$row){
				$this->load->model('permissions_db');
				$contributors = $this->permissions_db->getUsersWithPermission($row->collectionId);
				
				echo "<tr>";
				echo "<td> <a href='" . base_url() . "index.php/site/linkCollection?collectionId=" . $row->collectionId . "'>Link to Collection ID # " . $row->collectionId . "</a> </td>";
				echo "<td> Some Date </td>";
				echo "<td> [List Names on Collection's Columns] </td>";
				echo "<td>";
					foreach($contributors as $i=>$contributor){
						echo $contributor->username;
						echo " ";
					}
				echo "<a href='" . base_url() . "index.php/site/linkPermissions?collectionId=" . $row->collectionId . "'>(edit)</a> </td>";
				echo "<td> [No] </td>";
				echo "<td>" . 
					form_open('site/removeCollection', '', array('collectionId' => $row->collectionId)) . form_submit('removeRow', 'Remove') .  form_close() . "</td>";
				echo "</tr>";
			}

		?>

	</table>
	</br>

	<a href='<?php echo base_url()?>index.php/site/do_logout'>Logout</a>
	</br>
	<a href='<?php echo base_url()?>index.php/site/createNewCollectionForUser'>Start New Collection</a>

</div>

</body>
</html>