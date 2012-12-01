<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title; ?></title>
</head>
<body>

<div id="container">
	<h1>Subodh!</h1>
	
	</br>
	
	<table border="1" cellpadding="1" cellspacing="1" summary="Bill Splitting Table">
		<tr>
			<th>Item</th>
			<th>Amount</th>
			<th>Payer</th>
			<th>Friend 1</th>
			<th>Friend 2</th>
			<th>Friend 3</th>
			<th>Friend 4</th>
			<th>Friend 5</th>
		</tr>

		<?php

			foreach($results as $row){
				echo "<tr>";
				echo "<td>" . $row->item . "</td>";
				echo "<td>" . $row->amount . "</td>";
				echo "<td>" . $row->name . "</td>";
				echo "<td>" . form_checkbox(array('checked' => $row->friend1,'disabled' => 'disabled')) . "</td>";
				echo "<td>" . form_checkbox(array('checked' => $row->friend2,'disabled' => 'disabled')) . "</td>";
				echo "<td>" . form_checkbox(array('checked' => $row->friend3,'disabled' => 'disabled')) . "</td>";
				echo "<td>" . form_checkbox(array('checked' => $row->friend4,'disabled' => 'disabled')) . "</td>";
				echo "<td>" . form_checkbox(array('checked' => $row->friend5,'disabled' => 'disabled')) . "</td>";
				echo "</tr>";
			}

		?>

		<tr>

			<?php // need to create a function within site.php to add a row, but I dunno how to link to that function from here (below is just a guess)
			echo form_open('site/addBill'); ?> 
			<td><?php echo form_input('item', 'Item'); ?></td>
			<td><?php echo form_input('amount', 'Amount'); ?></td>
			<td><?php echo form_dropdown('payers', $options, ''); ?></td>
			<td><?php echo form_checkbox('friend1', 'accept', TRUE); ?></td>
			<td><?php echo form_checkbox('friend2', 'accept', TRUE); ?></td>
			<td><?php echo form_checkbox('friend3', 'accept', TRUE); ?></td>
			<td><?php echo form_checkbox('friend4', 'accept', TRUE); ?></td>
			<td><?php echo form_checkbox('friend5', 'accept', TRUE); ?></td>
		</tr>

	</table>
	<?php 
		echo form_submit('addbillsubmit', 'Add Bill!'); 
		echo form_close();
	?>

	<?php
		echo form_open('site/emptyBill');
		echo form_submit('emptyBillSubmit', 'Empty Table');
		echo form_close();
	?>




</div>

</body>
</html>