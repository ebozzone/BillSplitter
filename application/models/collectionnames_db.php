<?php

class Collectionnames_db extends CI_Model{

	function newCollectionName($collectionId){
		$data = array(
				'collectionId' => $collectionId,
				'collectionName' => "New Collection"
			);
		$this->db->insert("collectionnames", $data);
	}

	function getCollectionData($collectionId){
		$query = $this->db->query("SELECT * FROM collectionnames WHERE collectionId = '".$collectionId."'");
		return $query->result_array();
	}

	function addColumnToCollection($collectionId, $friendName)
	{
		//pull the numFriends from collectionNames
		$query = $this->db->query("SELECT friendCount FROM collectionnames WHERE collectionId = '".$collectionId."'");
		$result = $query->result_array();
		$numFriends = $result[0]['friendCount'];

		//if we're 15 already, do nothing
		if ($numFriends >= 15) {

		}
		else {
			//otherwise, increment, insert numFriends back
			$numFriends++;
			$query = $this->db->query("UPDATE collectionNames SET friendCount = ".$numFriends." WHERE collectionId = '".$collectionId."'");
			$query = $this->db->query("UPDATE collectionNames SET friend".$numFriends." = '".$friendName."' WHERE collectionId = '".$collectionId."'");
			//insert the friend name in "friend".$numFriends	
		}		
	}

	function deleteFriendColumnFromCollection($collectionId, $columnIndex)
	{
		//column index expects 0 - 14, not 1 - 15

		//name, checkboxes, friend count, shifting of names, shifting of checkboxes

		//check if it's okay to delete this person
		$validationQuery = $this->db->query("SELECT billId FROM billsummary WHERE collectionId = '".$collectionId."' AND name = ".$columnIndex);
		$validationResult = $validationQuery->result_array();
		if ($validationQuery->num_rows() > 0)
		{
			//there's an error
		}
		else
		{
			//remove the name, and shfit the names over adding a null at 15
			$nameQuery = $this->db->query("SELECT * FROM collectionnames WHERE collectionId = '".$collectionId."'");
			$result = $nameQuery->result_array();

			for ($i = $columnIndex + 1; $i < 15; $i++)
			{
				$result[0]['friend'.$i] = $result[0]['friend'.($i+1)];
			}
			$result[0]['friendCount'] = $result[0]['friendCount'] - 1;
			$result[0]['friend15'] = NULL;
			$this->db->where('collectionId', $collectionId);
			$this->db->update('collectionnames', $result[0]);

			//for each billId in the collectionId, remove "friend".$columnIndex
			$checkboxQuery = $this->db->query("SELECT * FROM billsummary WHERE collectionId = '".$collectionId."'");
			$checkboxResult = $checkboxQuery->result_array();

			foreach($checkboxResult as $row)
			{
				for ($j = $columnIndex + 1; $j < 15; $j++)
				{
					$row['friend'.$j] = $row['friend'.($j+1)];
				}
				if ($columnIndex < $row['name'])
				{
					$row['name'] = $row['name'] - 1;
				}
				$row['friend15'] = NULL;
				$this->db->where('collectionId', $collectionId);
				$this->db->where('billId', $row['billId']);
				$this->db->update('billsummary', $row);
			}

			//adjust the payers
		}
	}

}

?>