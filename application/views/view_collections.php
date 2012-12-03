<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title; ?></title>

	<script>

	</script>

</head>
<body>

<div id="container">
	<h1>Your Collections:</h1>
	
	</br>
	
	<table border="1" cellpadding="1" cellspacing="1" summary="Collections Table">
		<tr>
			<th>Name of Collection</th>
			<th>Date Created</th>
			<th>Friends</th>
		</tr>

		<?php

			foreach($results as $index=>$row){
				echo "<tr>";
				echo "<td> <a href='<?php echo site_url('site/home');?>'>My Collection</a> </td>";
				echo "<td> Some Date </td>";
				echo "<td> Your Friends </td>";
				echo "</tr>";
			}

		?>

	</table>

	<a href='<?php echo site_url('login');?>'>Logout</a>

</div>

</body>
</html>