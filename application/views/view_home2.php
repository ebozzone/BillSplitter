<!DOCTYPE html>
<html>

	<script>
		function byId(e){return document.getElementById(e);}
		
		//edit this to accept an argument whether to change the focus or not
		function makeTable(strParentId)
		{
			var table, tbody, curX, curY, curRow, curCell
			table = document.createElement('table');
			table.id = "itemsTable";
			tbody = document.createElement('tbody');
			var sideLen = 9;
			table.appendChild(tbody);
			table.className = "table table-striped table-bordered table-condensed";
			
			//hardcoded
			var headerNames = new Array("Item", "Amount", "Payer", "Me", "Friend1", "Friend2", "Friend3", "Friend4", "Friend5");

			//"inputs" from server
			var friendNamesArray = new Array("Manu", "Evan", "Bobert", "Jimothy", "Billiam", "Sergio");
			var billIdEntries = new Array(1, 2, 3, 4, 5, 6, 7, 8, 9);
			var itemEntries = new Array("mops", "trash bags", "milk", "oreos", "comcast bill", "socks", "cable", "repairs", "mousetrap");
			var amountEntries = new Array(12, 5, 4, 3, 98, 8, 56, 100);
			var payerEntries = new Array(0,1,2,0,0,0,2,3,4);

			//other arrays
			var entryNamesArray = new Array(itemEntries, amountEntries, payerEntries);			

			//header
			headerRow = document.createElement('tr');
			//headerRow.style.backgroundColor = "#989898";
			//headerRow.setAttribute("style", "background-color: #000080");
			
			var i = 0;
			var nextHeaderCell;
			var nextColumnDeleteButton;
			var nameToSend;

			//topRow (hidden)
			var topRow = document.createElement('tr');
			var spanCell = document.createElement('td');
			spanCell.setAttribute("colspan", "3");
			topRow.appendChild(spanCell);
			for (var t=0; t < headerNames.length - 3; t++)
			{
				nextHeaderCell = document.createElement('td');
				nextColumnDeleteButton = document.createElement('input');
				nextColumnDeleteButton.type = "button";
				nextColumnDeleteButton.value = "Delete";
				nextColumnDeleteButton.className = "columnDeleteButton";
				nextColumnDeleteButton.id = t; //friendNamesArray[t];
				nextColumnDeleteButton.onclick = function() {deleteColumn(this);}
				nextHeaderCell.appendChild(nextColumnDeleteButton);
				topRow.appendChild(nextHeaderCell);
			}
			var finalCell = document.createElement('td');
			topRow.appendChild(finalCell);
			topRow.hidden = true;
			topRow.id = "deleteColumnButtonsRow";
			tbody.appendChild(topRow);

			//header Row
			for (i =0; i < headerNames.length; i++)
			{
				nextHeaderCell = document.createElement('td');
				
				//first three columns are header names; last three have delete buttons for columns
				if (i < 3) {
					nextHeaderCell.appendChild(document.createTextNode(headerNames[i]));
				} else {
					//programatic header name
					nextHeaderCell.appendChild(document.createTextNode(friendNamesArray[i-3]));
					//nameToSend = friendNamesArray[i-2];
					nextHeaderCell.onclick = function() {onHeaderClick(this, nameToSend);}

					//column delete buttons
					/*nextColumnDeleteButton = document.createElement('input');
					nextColumnDeleteButton.type = "button";
					nextColumnDeleteButton.value = "X";
					nextColumnDeleteButton.className = "columnDeleteButton";
					nextColumnDeleteButton.id = friendNamesArray[i-3];
					nextColumnDeleteButton.hidden = true;
					nextColumnDeleteButton.onclick = function() {deleteColumn(this);}
					nextHeaderCell.appendChild(nextColumnDeleteButton);*/
				}

				headerRow.appendChild(nextHeaderCell);
			}
			// header for column with delete row buttons
			nextHeaderCell = document.createElement('td');
			nextHeaderCell.appendChild(document.createTextNode("  "));
			headerRow.appendChild(nextHeaderCell);

			//append the header to the body
			tbody.appendChild(headerRow);

			//body
			//ros
			var nextCheckbox; 
			var nextDropdown;
			var j;
			var numFriends = friendNamesArray.length;
			var deleteRowButton;
			for (curY=0; curY<sideLen; curY++)
			{
				curRow = document.createElement('tr');
				
				//two rows of entries
				for (curX=0; curX<2; curX++)
				{
					curCell = document.createElement('td');
					curCell.appendChild( document.createTextNode(entryNamesArray[curX][curY]));
					curCell.onclick = function() {onCellClick(this);}
					curCell.id = curX;
					curRow.appendChild(curCell);
				}

				//dropdown for this row
				curCell = document.createElement('td');
				nextDropdown = document.createElement('select');
				for (j = 0; j < numFriends; j++)
				{
					nextDropdown.options[j] = new Option(friendNamesArray[j], "friend"+j);
				}
				nextDropdown.onchange = function() {updateDropDown(this);}
				nextDropdown.selectedIndex = entryNamesArray[2][curY];
				curCell.appendChild(nextDropdown);
				curCell.id = 3;
				curRow.appendChild(curCell);
				

				//six rows of checkboxes
				for (curX=3; curX<sideLen; curX++)
				{
					curCell = document.createElement('td');
					nextCheckbox = document.createElement('input');
					nextCheckbox.type = "checkbox";
					nextCheckbox.name = "checkbox";
					nextCheckbox.value = "value";
					nextCheckbox.id = curX - 3;
					nextCheckbox.checked = true;
					nextCheckbox.onclick = function () {checkboxChanged(this);}
					curCell.appendChild(nextCheckbox);
					curCell.id = curX;
					//curCell.onclick = function() {onCellClick(this);}
					curRow.appendChild(curCell);
				}

				//delete this row button
				curCell = document.createElement('td');
				deleteRowButton = document.createElement('input');
				deleteRowButton.type = "button";
				deleteRowButton.value = " Delete ";
				deleteRowButton.className = "rowDeleteButton";
				deleteRowButton.id = billIdEntries[curY];
				deleteRowButton.onclick = function() {deleteRow(this);}
				curCell.appendChild(deleteRowButton);
				curRow.appendChild(curCell);

				//append this row to the table body
				curRow.id = billIdEntries[curY];
				tbody.appendChild(curRow);
			}

			//add the form row to the table

			//item input
			var formRow = document.createElement('tr');
			curCell = document.createElement('td');
			var itemInput = document.createElement('input');
			itemInput.onclick = function(e){this.select();}			
			itemInput.onkeyup = function(e){
				if (e.keyCode == '13')
				{
					addRowFormSubmitPressed();
				}
			}
			itemInput.value = "Enter Item";
			itemInput.id = "itemInput";
			itemInput.setAttribute("style", "text-align:left");
			curCell.appendChild(itemInput);
			formRow.appendChild(curCell);

			//amount Input
			curCell = document.createElement('td');
			var amountInput = document.createElement('input');
			itemInput.onclick = function(e){this.select();}
			//make this input only take 0 - 9 and . as inputs, ignore anything else!			
			amountInput.onkeyup = function(e){
				if (e.keyCode == '13')
				{
					addRowFormSubmitPressed();
				}
			}
			amountInput.value = "Enter Amount";
			amountInput.id = "amountInput";
			amountInput.setAttribute("style", "text-align:left");
			curCell.appendChild(amountInput);
			formRow.appendChild(curCell);

			//payer dropdown
			curCell = document.createElement('td');
			var payerInput = document.createElement('select');
			payerInput.id = "payerInput";
			for (j = 0; j < numFriends; j++)
			{
				payerInput.options[j] = new Option(friendNamesArray[j], "friend"+j);
			}
			payerInput.onkeyup = function(e){
				if (e.keyCode == '13')
				{
					addRowFormSubmitPressed();
				}
			}
			payerInput.selectedIndex = 1; //this selects which one is highlighted at the moment
			curCell.appendChild(payerInput);
			formRow.appendChild(curCell);

			//checkboxes
			var nextFormCheckbox;
			for (var m = 0; m < numFriends; m++) {
				curCell = document.createElement('td');
				nextFormCheckbox = document.createElement('input');
				nextFormCheckbox.type = "checkbox";
				nextFormCheckbox.id = "friend"+m;
				nextFormCheckbox.value = "friend"+m;
				nextFormCheckbox.class = "formCheckbox";
				console.log(nextFormCheckbox.id);
				nextFormCheckbox.checked = true;
				nextFormCheckbox.onkeyup = function(e){
				if (e.keyCode == '13')
				{
					addRowFormSubmitPressed();
				}
				}
				curCell.appendChild(nextFormCheckbox);
				formRow.appendChild(curCell);
			}

			tbody.appendChild(formRow);

			//place the table in the document; if this is a reload, just relace the old one
			var tableContainer = byId(strParentId);
			var oldTable;
			if (tableContainer.hasChildNodes()) {
				oldTable = document.getElementById("itemsTable");
				oldTable.parentNode.removeChild(oldTable);
			}
			byId(strParentId).appendChild(table);

			//ancillary stuff

			//add the button 
			var submitButton = document.createElement('input');
			submitButton.id = "submitButton";
			submitButton.type = "button";
			submitButton.value = "Add Item!";
			submitButton.onclick = function() {addRowFormSubmitPressed();}
			var buttonContainer = byId('submitButtonHolder');
			var oldSubmitButton;

			//if this is a reload, replace the old button
			if (buttonContainer.hasChildNodes()) {
				oldSubmitButton = document.getElementById("submitButton");
				oldSubmitButton.parentNode.removeChild(oldSubmitButton);
				submitButton.value = "Add Item!";
			}
			buttonContainer.appendChild(submitButton);

			//select the item input
			var inputTarget = $('#itemInput');
			inputTarget.focus();
			inputTarget.select();
			
		}

		//---------------
		//OTHER FUNCTIONS
		//---------------
		//---------------

		function onHeaderClick(clickedCell, placeText)
		{
			console.log("onHeaderClick called");
			var cellTxt = clickedCell.innerHTML;
			var editBox = document.createElement('input');
			editBox.value = cellTxt;
			clickedCell.removeChild(clickedCell.childNodes[0]);
			clickedCell.appendChild(editBox);
			clickedCell.onclick = '';
			editBox.focus();
			editBox.setAttribute("style", "text-align:left");
			editBox.select();
			editBox.onblur = function(){nameCellLoseFocus(this, cellTxt);}
			editBox.onkeyup = function(e){
				console.log("key is up:");
				console.log(e.keyCode);
				if (e.keyCode == '13')
				{	
					console.log("Enter Pressed");
					nameCellLoseFocus(this, cellTxt);
				}
				if (e.keyCode == '27')
				{
					console.log("Escape Pressed");
					escapeCell(this, cellTxt);
				}
			}
		}

		function nameCellLoseFocus(editedCell, previousText)
		{
			var cellTxt = editedCell.value;
			
			console.log("header cell's value is:"+editedCell.value);
			parentNode = editedCell.parentNode;
			editedCell.onblur = function() {return false;}
			parentNode.removeChild(editedCell);
			parentNode.appendChild( document.createTextNode(cellTxt) );
			parentNode.onclick = function() {onHeaderClick(this);}
			console.log("header focus lost");

			//check if the new value is different from the old
			if(cellTxt == previousText)
			{
				console.log("not sending anything to server, no change");
			}
			else {
				//it's different, so send the POST
				console.log("posting because the name has changed");

				//reload the table
				makeTable('tblHolder');
			}
		}
		
		function onCellClick(clickedCell)
		{
			var cellTxt = clickedCell.innerHTML;
			var editBox = document.createElement('input');
			editBox.value = cellTxt;
			clickedCell.removeChild(clickedCell.childNodes[0]);
			clickedCell.appendChild(editBox);
			clickedCell.onclick = '';
			editBox.focus();
			editBox.setAttribute("style", "text-align:left");
			editBox.select();
			editBox.onblur = function(){onLoseFocus(this);}
			editBox.onkeyup = function(e){
				console.log("key is up:");
				console.log(e.keyCode);
				if (e.keyCode == '13')
				{	
					console.log("Enter Pressed");
					onLoseFocus(this);
				}
				if (e.keyCode == '27')
				{
					console.log("Escape Pressed");
					escapeCell(this, cellTxt);
				}
			}
		}
 
		function onLoseFocus(editedCell)
		{
			var cellTxt = editedCell.value;
			console.log("edited cell's value is:"+editedCell.value);
			parentNode = editedCell.parentNode;
			editedCell.onblur = function() {return false;}
			parentNode.removeChild(editedCell);
			parentNode.appendChild( document.createTextNode(cellTxt) );
			parentNode.onclick = function() {onCellClick(this);}
			console.log("focus lost");

			//get the id of the row
			var columnId = parentNode.id;
			var rowId = parentNode.parentNode.id;

			//if columnId is zero, send to updateItem
			if (columnId == 0) {
				postUpdateOnItem(cellTxt, rowId);
			}
			if (columnId == 1) {
				postUpdateOnAmount(cellTxt, rowId);
			}

			//if there's a change, post
			
			//upon success, reload
			//add the argument to make sure focus is not set
			//makeTable('tblHolder');
		}

		function escapeCell(editedCell, previousText)
		{
			console.log(previousText);
			parentNode = editedCell.parentNode;
			editedCell.onblur = function(){return false;}
			parentNode.removeChild(editedCell);
			parentNode.appendChild(document.createTextNode(previousText));
			parentNode.onclick = function() {onCellClick(this);}
			console.log("escaped");
		}

		function checkboxChanged(checkbox) {

			//figure out value
			var value = checkbox.checked;

			//figure out billId
			var rowId = checkbox.parentNode.parentNode.id;

			//figure out friendIndex
			var friend = checkbox.id;

			//make the post
			postUpdateOnCheckbox(value, friend, rowId);
		}

		function toggleColumnDeleteButtons() 
		{
			//animated toggle of the column delete buttons 
			$("#deleteColumnButtonsRow").toggle('fast');
			
			//toggle the text of the delete button
			var btext = $('#deleteColumnsButton').attr('value');
			if (btext == 'Never Mind!') {
				$('#deleteColumnsButton').attr('value', 'Remove Friend');
			} else {
				$('#deleteColumnsButton').attr('value', 'Never Mind!');
			}
		}

		function deleteColumn(deleteColumnButton)
		{
			//check if any of the entries in payerArray match the number deleteColumnButton.id, then give an alert

			//otherwise, delete the column through POST
			console.log("Delete the column:");
			console.log(deleteColumnButton.id);

			//toggle, then reload the table
			toggleColumnDeleteButtons();
			makeTable('tblHolder');
		}


		function addColumn()
		{
			console.log("Adding a column!");
			//makes a post to add a column

			//within the return function:
			//the post returns data with json with a boolean saying whether the column addition was successful
				// if successful, reload the table
				//if unsuccessful, right below the button, put a sorry! You can't add any more friends
					//remove it after a timeer
					alert("Uh... sorry.");
		}

		function addRowFormSubmitPressed()
		{
			console.log("Form Submission was Hit!");

			//post the new row to the server

			//reload the table
			makeTable('tblHolder');
		}

		function deleteRow(deleteRowButton)
		{
			console.log("Delete Row Pressed for Row");
			console.log(deleteRowButton.id);

			//make a post to the server; send the bill ID to be deleted

			//upon success reload the table
			makeTable('tblHolder');
		}

		function postUpdateOnItem(value, billID)
		{
			//make a post for the particular value
			console.log("updating item "+value+" at billId "+billID);

			//no reload necessary	
		}

		function postUpdateOnAmount(value, billID)
		{
			console.log("updating amount "+value+" at billId "+billID);
		}

		function postUpdateOnPayer(value, billID)
		{

		}

		function postUpdateOnCheckbox(value, friend, billID)
		{
			console.log("updating with value: "+value+" friend: "+friend+" and billID: "+billID);

			//figure out which bill id / friend

			//make the post

			//change the local model
		}

		function updateDropDown(dropdown)
		{
			//find the bill ID to change (row #)
			var rowId = dropdown.parentNode.parentNode.id;

			//get the name of the payer
			//map dropdown.selectedIndex to the friend # for the database call
			var friendIndex = dropdown.selectedIndex; 

			//post the change
			console.log("Dropdown Called with BillID: "+rowId+" to friend #"+friendIndex);
			postUpdateOnPayer(friendIndex, rowId);

		}

 
	</script>

	<!-- END OF JAVASCRIPT TABLE CODE -->
	<!-- ============================ -->
	<!-- ============================ -->


<head>
	<title><?php echo $title; ?></title>
	<script src="<?php echo base_url(); ?>/jquery-1.8.3.min.js"></script>
	<!-- <link href="BillSplitter/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen"> -->
	<!-- <script src="BillSplitter/bootstrap/js/bootstrap.min.js"></script> -->
	<!--<script>
		$(document).ready(function(){
			$('#addPermissionsButton').click(function(){
				var button = $(this);
				$.post("addPermissions2", {origin: "home"}, function(data){
					var awesomeText = document.createTextNode(data);
					button.after(awesomeText);
					console.log(awesomeText);
				});
			});
		});-->



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
<body onload="makeTable('tblHolder');">

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

	<!-- HERE's WHERE THE JAVASCRIPT TABLE STUFF STARTS -->
	<!-- ============================================== -->

	<div id="tblButtonsHolder">Table Buttons Holder</div>
	<div id="tblHolder"> </div>
	<div id="inputsArea">
		<div id ="submitButtonHolder"></div>
		<!-- Can't do this yet, because there's no server running php! -->
		<!--<?php echo form_button(array('id' => 'submitButton', 'content' => 'Add Item!')); ?>-->
		<input id="addFriendButton" type="button" value="Add Friend" onclick="addColumn();" />
		<input id="deleteColumnsButton" type="button" value="Remove Friend" onclick="toggleColumnDeleteButtons();" />
	</div>

	<!-- HERE's WHERE THE JAVASCRIPT TABLE STUFF ENDS -->
	<!-- ============================================ -->
	

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