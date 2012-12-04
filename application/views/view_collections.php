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
			<th>Friends</th>
		</tr>

		<?php

			foreach($collections as $index=>$row){
				echo "<tr>";
				echo "<td> <a href='" . base_url() . "index.php/site/dashboardLink?collectionId=" . $row->collectionId . "'>Link to Collection ID # " . $row->collectionId . "</a> </td>";
				echo "<td> Some Date </td>";
				echo "<td> Your Friends </td>";
				echo "</tr>";
			}

		?>

	</table>
	</br>
	<a href='<?php echo base_url()?>index.php/site/do_logout'>Logout</a>

</div>

</body>
</html>