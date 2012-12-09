<!DOCTYPE html>
<html>
	<script>
		function byId(e){return document.getElementById(e);}
		
		//edit this to accept an argument whether to change the focus or not

		//select global variables
		var global_numFriends;
		var localModel = {}; 
		var amountsOwed = new Array();
		var amountsPaid = new Array();
		var showingTable = -1;
		//have itemizedArray of Item / Share / Owed (LATER)
		var itemizedArray = new Array();

		function loadCollectionName(strHolderId, collectionName)
		{
			var nameTable, ntbody, nRow, nCell;
			nameTable = document.createElement('table');
			nameTable.id = "collectionNameTable";
			ntbody = document.createElement('tbody');
			nRow = document.createElement('tr');
			nCell = document.createElement('td');
			nCell.appendChild(document.createTextNode(collectionName));
			nCell.onclick = function() {onCellClick(this);}
			nCell.id = "collectionNameCell";
			nRow.appendChild(nCell);
			ntbody.appendChild(nRow);

			nameTable.appendChild(ntbody);
			byId(strHolderId).innerHTML = "";
			byId(strHolderId).appendChild(nameTable);
		}

		function makeTable(strParentId, focus)
		{
			if(typeof(focus)==='undefined') focus = true;

			//Get the data to load the table from site/loadTable
			$.post("loadTable", {origin: "loadingtable"}, function(data){
					makeTable2(strParentId, data, focus);
				});

			$.post("collectionName", {}, function(data){
				loadCollectionName('collectionNameHolder', data);

			});

			//loadCollectionName('collectionNameHolder', '<?php echo $this->session->userdata('collectionName') ?>');
		}

		function makeTable2(strParentId, data, focus)
		{
			var table, tbody, curX, curY, curRow, curCell
			table = document.createElement('table');
			table.id = "itemsTable";
			tbody = document.createElement('tbody');
			var sideLen = 9;
			table.appendChild(tbody);
			table.className = "table table-striped table-bordered table-condensed";
			
			//parse data object into the various arrays

			var tableData = $.parseJSON(data);
			console.log(tableData);

			//friendNamesArray
			var friendsList = tableData['friends'];
			var raw_numFriends = tableData['numFriends'];
			global_numFriends = raw_numFriends;
			var numFriendsCounter = parseInt(raw_numFriends, 10) + 1;	
			//console.log("Num Friends:");
			//console.log(numFriendsCounter);
			var raw_friendNamesArray = new Array();
			for (var k = 1; k < numFriendsCounter; k++)
			{
				//console.log("Keys:");
				//console.log("friend"+k);
				//if (friendsList["friend"+k] == "") break;
				raw_friendNamesArray.push(friendsList["friend"+k]);
			}
			//console.log(raw_friendNamesArray);
			//console.log(friendsList);

			//Results
			var resultsArray = tableData['results'];
			var numberOfRows = resultsArray.length;

			var raw_billIdEntries = new Array();
			var raw_itemEntries = new Array();
			var raw_AmountEntries = new Array();
			var raw_payerEntries = new Array();
			var raw_checkboxes = new Array();

			//add the dimensions of the checkboxes;
			for (var m = 0; m < raw_numFriends; m++)
			{
				raw_checkboxes[m] = new Array();

			}

			for (var c = 0; c < numberOfRows; c++)
			{
				var dataRow = resultsArray[c];
				raw_billIdEntries.push(parseInt(dataRow['billId'],10)); 
				raw_itemEntries.push(dataRow['item']); 
				raw_AmountEntries.push(dataRow['amount']); 
				raw_payerEntries.push(parseInt(dataRow['name'],10));
				for (var n = 0; n < raw_numFriends; n++)
				{
					raw_checkboxes[n].push(parseInt(dataRow["friend"+(n+1)]));
				}
			}

			console.log("Formatted Data Into Arrays:");
			console.log(raw_billIdEntries); 
			console.log(raw_itemEntries); 
			console.log(raw_AmountEntries);
			console.log(raw_payerEntries);
			console.log(raw_checkboxes); 

			//hardcoded
			var headerNames = new Array("Item", "Amount", "Payer", "Me", "Friend1", "Friend2", "Friend3", "Friend4", "Friend5", "Friend6", "Friend7", "Friend8", "Friend9", "Friend10", "Friend11", "Friend12", "Friend13", "Friend14", "Friend15");

			//"inputs" from server
			var friendNamesArray = raw_friendNamesArray;//new Array("Manu", "Evan", "Bobert", "Jimothy", "Billiam", "Sergio");
			sideLen = friendNamesArray.length + 3;
			var billIdEntries = raw_billIdEntries;//new Array(1, 2, 3, 4, 5, 6, 7, 8, 9);
			var itemEntries = raw_itemEntries;//new Array("mops", "trash bags", "milk", "oreos", "comcast bill", "socks", "cable", "repairs", "mousetrap");
			var amountEntries = raw_AmountEntries;//new Array(12, 5, 4, 3, 98, 8, 56, 100);
			var payerEntries = raw_payerEntries;//new Array(0,1,2,0,0,0,2,3,4);

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
			for (var t=0; t < friendNamesArray.length; t++)
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
			for (i =0; i < friendNamesArray.length + 3; i++)
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
					nextHeaderCell.id = (i - 3);

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
			for (curY=0; curY<numberOfRows; curY++)
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
				

				//rows of checkboxes
				for (curX=3; curX<sideLen; curX++)
				{
					curCell = document.createElement('td');
					nextCheckbox = document.createElement('input');
					nextCheckbox.type = "checkbox";
					nextCheckbox.name = "checkbox";
					nextCheckbox.value = "value";
					nextCheckbox.id = curX - 3;
					//nextCheckbox.checked = true;
					nextCheckbox.checked = raw_checkboxes[curX - 3][curY];
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
			payerInput.selectedIndex = 0; //this selects which one is highlighted at the moment
			curCell.appendChild(payerInput);
			formRow.appendChild(curCell);

			//checkboxes
			var nextFormCheckbox;
			for (var m = 0; m < numFriends; m++) {
				curCell = document.createElement('td');
				nextFormCheckbox = document.createElement('input');
				nextFormCheckbox.type = "checkbox";
				nextFormCheckbox.id = "checkboxForFriend"+m;
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


			//load the local model
			localModel['friendNamesArray'] = raw_friendNamesArray;
			localModel['billIdEntries'] = raw_billIdEntries;
			localModel['itemEntries'] = raw_itemEntries;
			localModel['amountEntries'] = raw_AmountEntries;
			localModel['payerEntries'] = raw_payerEntries;
			localModel['checkboxes'] = raw_checkboxes;
			runCalculation();

			//select the item input (IF THE OPTION IS REQUESTED)
			if (focus)
			{
				var inputTarget = $('#itemInput');
				inputTarget.focus();
				inputTarget.select();
			}
			
		}

		//---------------
		//OTHER FUNCTIONS
		//---------------
		//---------------

		function runCalculation()
		{
			var numFriends = localModel['friendNamesArray'].length;
			console.log("Num Friends: "+numFriends);
			var numRows = localModel['billIdEntries'].length;
			itemizedArray = new Array();
			

			//initialize amounts paid and amounts owed
			for (var j = 0; j < numFriends; j++)
			{
				amountsPaid[j] = 0;
				amountsOwed[j] = 0;

				var nextItemizedTable = {}
				nextItemizedTable['items'] = new Array();
				nextItemizedTable['participants'] = new Array();
				nextItemizedTable['amounts'] = new Array();

				itemizedArray[j] = nextItemizedTable;
			}

			//initialize the itemized Array


			//Calculate amounts owed, row by row
			for (var i = 0; i < numRows; i++) 
			{
				//figure out the individual contribution
				var itemCost = localModel['amountEntries'][i];
				var numParticipants = 0;
				for (var k = 0; k < numFriends; k++)
				{
					numParticipants = numParticipants + localModel['checkboxes'][k][i];
				}
				var individualContribution = 0;
				if (numParticipants > 0) 
				{
					individualContribution = (itemCost/numParticipants);
				}

				//for each participant, add the individual contribution if they participated
				for (var l = 0; l < numFriends; l++)
				{
					if (localModel['checkboxes'][l][i] == 1)
					{
						amountsOwed[l] = amountsOwed[l] + individualContribution;

						//here's where you add the itemized tables
						itemizedArray[l]['items'].push(localModel['itemEntries'][i]);
						itemizedArray[l]['participants'].push(numParticipants);
						itemizedArray[l]['amounts'].push(individualContribution);

					}
				}
			}

			//Calculate amounts paid, row by row
			for (var m = 0; m < numRows; m++)
			{
				var payerNumber = localModel['payerEntries'][m];
				amountsPaid[payerNumber] = parseFloat(amountsPaid[payerNumber]) + parseFloat(localModel['amountEntries'][m]);
			}

			console.log("Amounts Owed, Paid:")
			console.log(amountsOwed);
			console.log(amountsPaid);

			updateResultsArea();

		}

		function updateResultsArea()
		{
			var resultsTable = document.createElement('table');
			resultsTable.id = "resultsTable";
			var rtbody = document.createElement('tbody');

			//add a row for each friend, including "X's share of the expenses is: [] But X paid: []. So X owes: []"
			var numFriends = localModel['friendNamesArray'].length;
			var nextRow;
			var nextCell;
			var textHolder;
			var itemizedDetailButton;
			var discrepancy;
			for (var i = 0; i < numFriends; i++)
			{
				nextRow = document.createElement('tr');

				//text1 cell
				nextCell = document.createElement('td');
				textHolder = localModel['friendNamesArray'][i]+"'s share of the expenses is:";
				nextCell.appendChild(document.createTextNode(textHolder));
				nextRow.appendChild(nextCell);

				//owes cell
				nextCell = document.createElement('td');
				nextCell.appendChild(document.createTextNode(amountsOwed[i].toFixed(2)));
				nextRow.appendChild(nextCell);

				//paid cell
				nextCell = document.createElement('td');
				textHolder = "But "+localModel['friendNamesArray'][i]+" paid:";
				nextCell.appendChild(document.createTextNode(textHolder));
				nextRow.appendChild(nextCell);

				//paid cell
				nextCell = document.createElement('td');
				nextCell.appendChild(document.createTextNode(amountsPaid[i].toFixed(2)));
				nextRow.appendChild(nextCell);

				//nextCell = document.createElement('td');
				nextCell = document.createElement('td');
				textHolder = "So he/she owes:";
				nextCell.appendChild(document.createTextNode(textHolder));
				nextRow.appendChild(nextCell);

				//paid cell
				nextCell = document.createElement('td');
				discrepancy = amountsOwed[i] - amountsPaid[i];
				nextCell.appendChild(document.createTextNode(discrepancy.toFixed(2)));
				nextRow.appendChild(nextCell);

				//itemized button
				nextCell = document.createElement('td');
				itemizedDetailButton = document.createElement('input');
				itemizedDetailButton.type = "button";
				itemizedDetailButton.value = " Detail ";
				itemizedDetailButton.className = "itemizedDetailButton";
				itemizedDetailButton.id = i;
				itemizedDetailButton.onclick = function() {showItemizedForButton(this);}
				nextCell.appendChild(itemizedDetailButton);
				nextRow.appendChild(nextCell);

				//append row
				rtbody.appendChild(nextRow);
			}

			resultsTable.appendChild(rtbody);

			var resultsTableContainer = byId('resultsTableDiv');
			var oldResultsTable;
			if (resultsTableContainer.hasChildNodes()) {
				oldResultsTable = document.getElementById("resultsTable");
				oldResultsTable.parentNode.removeChild(oldResultsTable);
			}
			//document.getElementById('resultsTableArea').appendChild(document.createTextNode("Hello!"));			
			byId('resultsTableDiv').appendChild(resultsTable);		

			updateItemizedTables();	

		}

		function showItemizedForButton(itemizedDetailButton)
		{
			var numFriends = localModel['friendNamesArray'].length;
			console.log("Detail Pressed "+itemizedDetailButton.id);

			for (var i = 0; i < numFriends; i++)
			{
				if (i == itemizedDetailButton.id)
				{
					if (showingTable == i)
					{
						$(document.getElementById("itemizedTable"+(parseInt(i) + 1))).fadeOut('fast');	
						showingTable = -1;
					}
					else 
					{
						$(document.getElementById("itemizedTable"+(parseInt(i) + 1))).fadeIn('fast');
						showingTable = i;
					}
				}
				else
				{
					$(document.getElementById("itemizedTable"+(parseInt(i) + 1))).fadeOut('fast');	
				}
			}
		}

		function updateItemizedTables() 
		{
			//give them each Ids itemizedTable1 etc
			var numFriends = localModel['friendNamesArray'].length;

			var nextItemizedTable;
			var nextTableBody;
			var nextRow;
			var nextCell;
			var nextDisplayAmount;
			var nextDisplayDiscrepancy;

			var itemizedTableContainer = byId('itemizedTableDiv');
				itemizedTableContainer.innerHTML = "";

			for (var i = 0; i < numFriends; i++)
			{
				nextItemizedTable = document.createElement('table');
				nextItemizedTable.id = "itemizedTable"+(parseInt(i) + 1);
				if (i != showingTable) nextItemizedTable.hidden = true;
				nextTableBody = document.createElement('tbody');

				//Header 1 Row
				nextRow = document.createElement('tr');
				nextCell = document.createElement('td');
				nextCell.appendChild(document.createTextNode("Itemized Table For "+localModel['friendNamesArray'][i]));
				nextCell.colSpan = 3;
				nextRow.appendChild(nextCell);
				nextTableBody.appendChild(nextRow);

				//Header 2 Row
				nextRow = document.createElement('tr');
				nextCell = document.createElement('td');
				nextCell.appendChild(document.createTextNode("Item"));
				nextRow.appendChild(nextCell);
				nextCell = document.createElement('td');
				nextCell.appendChild(document.createTextNode("Share"));
				nextRow.appendChild(nextCell);
				nextCell = document.createElement('td');
				nextCell.appendChild(document.createTextNode("Amount"));
				nextRow.appendChild(nextCell);
				nextTableBody.appendChild(nextRow);
				
				//Rows (test for right now)

				var iterations = itemizedArray[i]['items'].length;
				for (var j = 0; j < iterations; j++)
				{
					//if person i participated in item entry j, then go ahead and add  a row
					//var participation = localModel['checkboxes'][] 

					nextRow = document.createElement('tr');
					nextCell = document.createElement('td');
					nextCell.appendChild(document.createTextNode(itemizedArray[i]['items'][j]));
					nextRow.appendChild(nextCell);
					nextCell = document.createElement('td');
					var friendsInvolved = itemizedArray[i]['participants'][j];
					var myShareText;
					if (friendsInvolved == 1)
					{
						myShareText = "All";
					}
					else
					{
						myShareText = "1/"+friendsInvolved;
					}
					nextCell.appendChild(document.createTextNode(myShareText));
					nextRow.appendChild(nextCell);
					nextCell = document.createElement('td');
					nextCell.appendChild(document.createTextNode(itemizedArray[i]['amounts'][j].toFixed(2)));
					nextRow.appendChild(nextCell);
					nextTableBody.appendChild(nextRow);
				}

				//Footer Rows
				//Total Row
				nextRow = document.createElement('tr');
				nextCell = document.createElement('td');
				nextCell.appendChild(document.createTextNode("Total"));
				nextCell.colSpan = 2;
				nextRow.appendChild(nextCell);
				nextCell = document.createElement('td');
				nextCell.appendChild(document.createTextNode(amountsOwed[i].toFixed(2)));
				nextRow.appendChild(nextCell);
				nextTableBody.appendChild(nextRow);
				
				//Already Paid Row
				nextRow = document.createElement('tr');
				nextCell = document.createElement('td');
				nextCell.appendChild(document.createTextNode(localModel['friendNamesArray'][i]+" already paid:"));
				nextCell.colSpan = 2;
				nextRow.appendChild(nextCell);
				nextCell = document.createElement('td');
				nextCell.appendChild(document.createTextNode(amountsPaid[i].toFixed(2)));
				nextRow.appendChild(nextCell);
				nextTableBody.appendChild(nextRow);

				//Final Tally Row
				nextRow = document.createElement('tr');
				nextCell = document.createElement('td');
				nextCell.appendChild(document.createTextNode("Therefore "+localModel['friendNamesArray'][i]+" owes:"));
				nextCell.colSpan = 2;
				nextRow.appendChild(nextCell);
				nextCell = document.createElement('td');
				nextDisplayDiscrepancy = amountsOwed[i] - amountsPaid[i];
				nextCell.appendChild(document.createTextNode(nextDisplayDiscrepancy.toFixed(2)));
				nextRow.appendChild(nextCell);
				nextTableBody.appendChild(nextRow);

				//append boy and table
				nextItemizedTable.appendChild(nextTableBody);			
				byId('itemizedTableDiv').appendChild(nextItemizedTable);	
			}
		}

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
				console.log("posting because the name has changed for ID = "+parentNode.id);

				$.post("updateFriendName", { friendId: parentNode.id, newName: cellTxt }, function(data){
					console.log("Updating:");
					console.log(data);
					makeTable('tblHolder', false);
				});

				//reload the table
				//makeTable('tblHolder');
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
			
			if (clickedCell.id == "collectionNameCell") {
				editBox.onblur = function(){onTitleLoseFocus(this);}
			} else {
				editBox.onblur = function(){onLoseFocus(this);}
			}

			editBox.onkeyup = function(e){
				console.log("key is up:");
				console.log(e.keyCode);
				if (e.keyCode == '13')
				{	
					console.log("Enter Pressed");
					if (clickedCell.id == "collectionNameCell") {
						onTitleLoseFocus(this);
					} else {
						onLoseFocus(this);
					}
					
				}
				if (e.keyCode == '27')
				{
					console.log("Escape Pressed");
					escapeCell(this, cellTxt);
				}
			}
		}

		function onTitleLoseFocus(editedCell)
		{
			var cellTxt = editedCell.value;
			console.log("edited cell's value is:"+editedCell.value);
			parentNode = editedCell.parentNode;
			editedCell.onblur = function() {return false;}
			parentNode.removeChild(editedCell);
			parentNode.appendChild( document.createTextNode(cellTxt) );
			parentNode.onclick = function() {onCellClick(this);}
			console.log("focus lost");

			//make a post changing the name of the title, update the name cell afterwards
			$.post("changeCollectionName", { newCollectionName: editedCell.value.replace(/'/g, "\\'") }, function(data){
					loadCollectionName("collectionNameHolder", data);
				});
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

			$.post("deleteColumn", { friendToDelete: deleteColumnButton.id }, function(data){
					makeTable('tblHolder');
				});


			//makeTable('tblHolder');
		}


		function addColumn()
		{
			console.log("Adding a column!");
			//makes a post to add a column

			if (global_numFriends > 14) {
				alert("Uh... sorry. You don't really have that many friends. Douchebag.");
			}
			else {


				$.post("addColumn", { newColumnName: "new friend" }, function(data){
					makeTable('tblHolder');
				});
			}
			//within the return function:
			//the post returns data with json with a boolean saying whether the column addition was successful
				// if successful, reload the table
				//if unsuccessful, right below the button, put a sorry! You can't add any more friends
					//remove it after a timeer
					
		}

		function addRowFormSubmitPressed()
		{
			console.log("Form Submission was Hit!");

			var dataToSend = {};
			dataToSend.item = document.getElementById('itemInput').value;
			dataToSend.amount = document.getElementById('amountInput').value;
			dataToSend.payers = document.getElementById('payerInput').selectedIndex;
			for (var d = 0; d < global_numFriends; d++)
			{
				var checkValue = 0;
				if (document.getElementById('checkboxForFriend'+d).checked)
				{
					checkValue = 1;
				}

				dataToSend['friend'+(d+1)] = checkValue;
			}

			console.log(dataToSend);
			//post the new row to the server
			$.post("addBill", dataToSend, function(data){
					makeTable('tblHolder');
				});

			//reload the table
			//makeTable('tblHolder');
		}

		function deleteRow(deleteRowButton)
		{
			console.log("Delete Row Pressed for Row");
			console.log(deleteRowButton.id);

			//make a post to the server; send the bill ID to be deleted
			$.post("deleteItem", { rowId: deleteRowButton.id }, function(data){
					makeTable('tblHolder', false);
				});

			//upon success reload the table
			//makeTable('tblHolder');
		}

		function postUpdateOnItem(value, billID)
		{
			//make a post for the particular value
			console.log("updating item "+value+" at billId "+billID);
			var newValue = value;
			newValue = newValue.replace(/'/g, "\\'");
			$.post("updateItem", { newItem: newValue, billId: billID}, function(data){
					makeTable('tblHolder', false);
				});

			//no reload necessary	
		}

		function postUpdateOnAmount(value, billID)
		{
			console.log("updating amount "+value+" at billId "+billID);
			$.post("updateAmount", { newAmount: value, billId: billID}, function(data){
					makeTable('tblHolder', false);
				});
		}

		function postUpdateOnPayer(value, billID)
		{
			console.log("updating amount "+value+" at billId "+billID);
			$.post("updatePayer", { newPayer: value, billId: billID}, function(data){
					makeTable('tblHolder', false);
				});
		}

		function postUpdateOnCheckbox(value, friend, billID)
		{
			//figure out which bill id / friend
			var intValue = 0;
			if (value) intValue = 1;

			console.log("updating with value: "+intValue+" friend: "+friend+" and billID: "+billID);

			//make the post
			$.post("updateCheckbox", { friendId: friend, newCheck: intValue, billId: billID}, function(data){
					console.log("reply:");
					console.log(data);
					makeTable('tblHolder', false);
				});

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
	<style>
	td{
		width: 32px;
		text-align: right;
		height: 20px;
	}
	td input
	{
		height:16px;
		text-align:center;
		border: none;
		width: 100%;
	}
	</style>
<head>
	<title>Test</title>
	<link href="<?php echo base_url(); ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<!--<script src="BillSplitter/jquery-1.8.3.min.js"></script>-->
	<script src="<?php echo base_url(); ?>/jquery-1.8.3.min.js"></script>
	<!--<script src="http://code.jquery.com/jquery-latest.js"></script>-->
    <script src="<?php echo base_url(); ?>/bootstrap/js/bootstrap.min.js"></script>
</head>
<body onload="makeTable('tblHolder');">
	
	<h1>Welcome to BillSplitter, <?php 
		if($this->session->userdata('username') == NULL){
			echo "Homey";
		}
		else{
			echo $this->session->userdata('fname'); 
		}
	?>! </h1>
	
	<h2><?php echo $this->session->userdata('collectionName') ?></h2>	
	<div id="collectionNameHolder"></div>
	

	<?php 
		if($this->session->userdata('username') == NULL){
			echo "<a href='" . base_url() . "index.php/login/createAccount'>Save this collection for later</a>";
		}
		else{
			echo "<a href='" . base_url() . "index.php/site/collectionsList'>Back to List of Collections</a>";
		}
	?>
	<div id="tblButtonsHolder"></div>
	<div id="tblHolder"></div>
	<div id="inputsArea">
		<div id ="submitButtonHolder"></div>
		<!-- Can't do this yet, because there's no server running php! -->
		<!--<?php echo form_button(array('id' => 'submitButton', 'content' => 'Add Item!')); ?>-->
		<input id="addFriendButton" type="button" value="Add Friend" onclick="addColumn();" />
		<input id="deleteColumnsButton" type="button" value="Remove Friend" onclick="toggleColumnDeleteButtons();" />
	</div>
	<div id="resultsArea"> Stuff is here
		<div id="resultsTableDiv"></div>
		<div id="itemizedTableDiv"></div>
	</div>
</body>
</html>