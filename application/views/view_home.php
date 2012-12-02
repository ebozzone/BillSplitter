<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title; ?></title>

	<script>
		<!--
			/*window.onload = function() {

				var deleteButtons = document.getElementsByClassName('deleteButton');
				var buttonsCount = deleteButtons.length;
				for (var i = 0; i <= buttonsCount; i += 1) {
					deleteButtons[i].onclick = function() {
						var buttonId = this.id;
						var form = document.createElement('form');
						form.setAttribute('method', 'post');
						form.setAttribute('action', 'deleteItem');
						form.style.display = 'hidden';
						var dataField = document.createElement("rowId");
						dataField.setAttribute("name", "buttonId");
						dataField.setAttribute("value", buttonId);
						form.appendChild(dataField);
						document.body.appendChild(form);
						form.submit();
					}
				}
			}*/

		-->
	</script>

</head>
<body>

<div id="container">
	<h1>Subdoh Is What Froot Said</h1>
	
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
			<th>Actions</th>
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
				echo "<td>" . form_open('site/deleteItem', '', array('rowId' => $row->billId)) . form_submit('deleteRow', 'Delete!') .  form_close() . "</td>";
				//echo "<td>" . form_button(array('name' => 'deleteRow', 'id' => 'deleteRow'.$index, 'content' => 'Delete', 'class' => 'deleteButton')) . "</td>";
				echo "</tr>";
			}

		?>

		<tr>

			<?php // need to create a function within site.php to add a row, but I dunno how to link to that function from here (below is just a guess)
			echo form_open('site/addBill'); ?> 
			<td><?php echo form_input(array('name' => 'item', 'value' => 'Item', 'autofocus' => 'autofocus')); ?></td>
			<td><?php echo form_input('amount', 'Amount'); ?></td>
			<td><?php echo form_dropdown('payers', $options, ''); ?></td>
			<td><?php echo form_checkbox('friend1', 1, TRUE); ?></td>
			<td><?php echo form_checkbox('friend2', 1, TRUE); ?></td>
			<td><?php echo form_checkbox('friend3', 1, TRUE); ?></td>
			<td><?php echo form_checkbox('friend4', 1, TRUE); ?></td>
			<td><?php echo form_checkbox('friend5', 1, TRUE); ?></td>
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