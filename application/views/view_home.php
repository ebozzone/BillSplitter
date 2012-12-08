<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title; ?></title>
	<script src="<?php echo base_url(); ?>/jquery-1.8.3.min.js"></script>
	<script>
		$(document).ready(function(){
			$('#addPermissionsButton').click(function(){
				var button = $(this);
				$.post("addPermissions2", {origin: "home"}, function(data){
					var awesomeText = document.createTextNode(data);
					button.after(awesomeText);
					console.log(awesomeText);
				});
			});
		});



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
	<h1>Welcome to BillSplitter, <?php 
		if($this->session->userdata('username') == NULL){
			echo "Homey";
		}
		else{
			echo $this->session->userdata('username'); 
		}
	?>! </h1>
	
	<h2><?php echo $this->session->userdata('collectionName') ?></h2>	
	

	<?php 
		if($this->session->userdata('username') == NULL){
			echo "<a href='" . base_url() . "index.php/login/createAccount'>Save this collection for later</a>";
		}
		else{
			echo "<a href='" . base_url() . "index.php/site/collectionsList'>Back to List of Collections</a>";
		}
	?>

	

	</br>
	</br>

	<?php print_r($friends); ?>
	
	<table border="1" cellpadding="1" cellspacing="1" summary="Bill Splitting Table">
		<tr>
			<th>Item</th>
			<th>Amount</th>
			<th>Payer</th>
			<?php
				for($i = 1; $i < $numFriends + 1; $i++){
					$friendName = 'friend'.$i;
					echo "<th>" . $friends[$friendName] . "</th>";
				}
				for($i = $numFriends + 1; $i < 16; $i++){
					$friendName = 'friend'.$i;
					echo "<th>" . $friendName . "</th>";
				}
			?>
			<th>Actions</th>
		</tr>

		<?php

			foreach($results as $index=>$row){
				echo "<tr>";
				echo "<td>" . $row->item . "</td>";
				echo "<td>" . $row->amount . "</td>";
				echo "<td>" . $row->name . "</td>";

				echo "<td>" . form_checkbox(array('checked' => $row->friend1,'disabled' => 'disabled')) . "</td>";
				echo "<td>" . form_checkbox(array(	'checked' => $row->friend2,'disabled' => 'disabled')) . "</td>";
				echo "<td>" . form_checkbox(array('checked' => $row->friend3,'disabled' => 'disabled')) . "</td>";
				echo "<td>" . form_checkbox(array('checked' => $row->friend4,'disabled' => 'disabled')) . "</td>";
				echo "<td>" . form_checkbox(array('checked' => $row->friend5,'disabled' => 'disabled')) . "</td>";
				echo "<td>" . form_checkbox(array('checked' => $row->friend6,'disabled' => 'disabled')) . "</td>";
				echo "<td>" . form_checkbox(array('checked' => $row->friend7,'disabled' => 'disabled')) . "</td>";
				echo "<td>" . form_checkbox(array('checked' => $row->friend8,'disabled' => 'disabled')) . "</td>";
				echo "<td>" . form_checkbox(array('checked' => $row->friend9,'disabled' => 'disabled')) . "</td>";
				echo "<td>" . form_checkbox(array('checked' => $row->friend10,'disabled' => 'disabled')) . "</td>";
				echo "<td>" . form_checkbox(array('checked' => $row->friend11,'disabled' => 'disabled')) . "</td>";
				echo "<td>" . form_checkbox(array('checked' => $row->friend12,'disabled' => 'disabled')) . "</td>";
				echo "<td>" . form_checkbox(array('checked' => $row->friend13,'disabled' => 'disabled')) . "</td>";
				echo "<td>" . form_checkbox(array('checked' => $row->friend14,'disabled' => 'disabled')) . "</td>";
				echo "<td>" . form_checkbox(array('checked' => $row->friend15,'disabled' => 'disabled')) . "</td>";
				echo "<td>" . form_open('site/deleteItem', '', array('rowId' => $row->billId)) . form_submit('deleteRow', 'Delete!') .  form_close() . "</td>";
				//echo "<td>" . form_button(array('name' => 'deleteRow', 'id' => 'deleteRow'.$index, 'content' => 'Delete', 'class' => 'deleteButton')) . "</td>";
				echo "</tr>";
			}

		?>

		<tr>

			<?php
			echo form_open('site/addBill'); ?> 
			<td><?php echo form_input(array('name' => 'item', 'value' => 'Item', 'autofocus' => 'autofocus')); ?></td>
			<td><?php echo form_input('amount', 'Amount'); ?></td>
			<td><?php 
				$dropdownValues = array();
				for ($l = 0; $l < $numFriends; $l++) {
					$friendName = 'friend'.($l+1);
					$dropdownValues[$l] = $friends[$friendName];
				}
				echo form_dropdown('payers', $dropdownValues, ''); 
				//echo form_dropdown('payers', $friends, ''); 
			?></td>
			<td><?php echo form_checkbox('friend1', 1, TRUE); ?></td>
			<td><?php echo form_checkbox('friend2', 1, TRUE); ?></td>
			<td><?php echo form_checkbox('friend3', 1, TRUE); ?></td>
			<td><?php echo form_checkbox('friend4', 1, TRUE); ?></td>
			<td><?php echo form_checkbox('friend5', 1, TRUE); ?></td>
			<td><?php echo form_checkbox('friend6', 1, TRUE); ?></td>
			<td><?php echo form_checkbox('friend7', 1, TRUE); ?></td>
			<td><?php echo form_checkbox('friend8', 1, TRUE); ?></td>
			<td><?php echo form_checkbox('friend9', 1, TRUE); ?></td>
			<td><?php echo form_checkbox('friend10', 1, TRUE); ?></td>
			<td><?php echo form_checkbox('friend11', 1, TRUE); ?></td>
			<td><?php echo form_checkbox('friend12', 1, TRUE); ?></td>
			<td><?php echo form_checkbox('friend13', 1, TRUE); ?></td>
			<td><?php echo form_checkbox('friend14', 1, TRUE); ?></td>
			<td><?php echo form_checkbox('friend15', 1, TRUE); ?></td>
		</tr>

	</table>
	<?php 
		echo form_submit('addbillsubmit', 'Add Bill!'); 
		echo form_close();
	?>

	<?php
		//echo form_open('site/emptyBill');
		//echo form_submit('emptyBillSubmit', 'Empty Table');
		//echo form_close();
	?>

	<?php
		echo form_open('site/addColumn');
		echo form_input('newColumnName', "Friend's Name");
		echo form_submit('newColumnSubmit', 'Submit Column!'); 
		echo form_close();
	?>

	<?php
		echo form_open('site/deleteColumn');

		$columnDropdownValues = array();
		for ($m = 0; $m < $numFriends; $m++) {
			$friendName = 'friend'.($m+1);
			$columnDropdownValues[$m] = $friends[$friendName];
		}
		echo form_dropdown('friendToDelete', $dropdownValues, ''); 
		echo form_submit('newColumnSubmit', 'Delete Friend'); 
		echo form_close();
	?>

	<p><b>Share this Collection with friends:</b></p>
	<?php 
		//echo form_open('site/addPermissions', '', array('collectionId' => $this->session->userdata('collectionId'), 'origin' => 'home'));
		echo form_input(array('id' => 'addPermissionsInput', 'name' => 'emails', 'value' => 'Enter Email(s) Separated by Commas', 'style' => 'width:300px'));
		echo form_button(array('id' => 'addPermissionsButton', 'content' => 'Share'));
		//echo form_submit('addPermissions', 'Share');
		//echo form_close();
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

	</br><a href='<?php echo base_url()?>index.php/site/do_logout'>Logout</a>

</div>

<div id = "results">
	<h2>Here are your results:</h2>
	</br>
	<ul>
		<?php
			for($i = 1; $i < 6; $i++){
				$friendName = 'friend'.$i;
				$discrepancy = $amountsOwed[$friendName] - $amountsPaid[$friendName];
				echo "<li> " . $friends[$friendName] . " paid $" . $amountsPaid[$friendName] . " and his share of the expenses is $" . $amountsOwed[$friendName] . ". He owes <b>$" . $discrepancy . "</b>.</li>";				
			}
		?>
	</ul>
</div>

</body>
</html>