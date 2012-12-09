<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title; ?></title>
	<link href="<?php echo base_url(); ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<style type="text/css">

      /* Custom container */
      .container-narrow {
        margin: 0 auto;
        max-width: 800px;
      }
      .container-narrow > hr {
        margin: 30px 0;
      }

      .table-striped tr { height: 14px; } 

    </style>

	<script>

	</script>

</head>
<body>
<script src="<?php echo base_url(); ?>/jquery-1.8.3.min.js"></script>
<script src="<?php echo base_url(); ?>/bootstrap/js/bootstrap.min.js"></script>
	<!--BillSpliter banner -->
	<a style="display:block" href="<?php echo base_url(); ?>">
		<div style="display: table; height: 100px; #position: relative; overflow: hidden; background-color:#00297A; width:100%">
    		<div style=" #position: absolute; #top: 50%;display: table-cell; vertical-align: middle; padding-left:20px">
      			<div class="greenBorder" style=" #position: relative; #top: -50%">
        			<font color="white"><h1>BillSplit.it</h1></font>
			     </div>
		    </div>
		</div>
	</a>

<div id="container" class="container-narrow">
	</br>
	<h1><?php echo $this->session->userdata('fname') ?>'s List of Collections:</h1>
	
	</br>
	
	<center><table id="collectionsTable" class="table table-striped table-hover" border="1" cellpadding="1" cellspacing="1" summary="Collections Table" style="table-layout:fixed;">
		<col width="200px" />
   	 	<col width="400px" />
    	<col width="130px" />
    	<col width="110px" />
		<tr>
			<th><h4>Collections</h4></th>
			<th><h4>People Splitting the Bill</h4></th>
			<th><h4>Permissions</h4></th>
			<th><h4>Actions</h4></th>
		</tr>

		<?php

			foreach($collections as $index=>$row){
				
				//load the database for permissions
				$this->load->model('permissions_db');
				$contributors = $this->permissions_db->getUsersWithPermission($row->collectionId);
				$numFriends = $row->friendCount;
				echo "<tr>";
				echo "<td> <a href='" . base_url() . "index.php/site/linkCollection?collectionId=" . $row->collectionId . "'>" . $row->collectionName . "</a> </td>";
				//echo "<td>" . /*date("F j, Y", strtotime($row->creationDate))*/"Date Here" . "</td>";
				echo "<td>";
					for ($i = 1; $i < $numFriends; $i++) {
						$temp = 'friend'.$i;
						echo $row->$temp;
						echo ", ";
					}
					if ($numFriends != 0) 
					{
						$temp = 'friend'.$numFriends;
						echo $row->$temp;
					}
				echo "</td>";
				echo "<td>";
					//foreach($contributors as $i=>$contributor){
					//	echo $contributor->username;
					//	echo " ";
					//}
				echo "<a href='" . base_url() . "index.php/site/linkPermissions?collectionId=" . $row->collectionId . "'>Edit</a> </td>";
				//echo "<td> [No] </td>";
				echo "<td>" . 
					form_open('site/removeCollection', '', array('collectionId' => $row->collectionId)) . form_submit('removeRow', 'Remove') .  form_close() . "</td>";
				echo "</tr>";
			}

		?>

	</table></center>
	

	<a href='<?php echo base_url()?>index.php/site/createNewCollectionForUser'><h3>Start New Collection</h3></a>
	<a href='<?php echo base_url()?>index.php/site/do_logout'>Logout</a>
</div>

</body>
</html>