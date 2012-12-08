<?php

class Get_db extends CI_Model{
	function getAll($collectionId){
		$query = $this->db->query("SELECT * FROM billsummary WHERE collectionId = ".$collectionId);
		return $query->result();
	}

	function insertNewBill($data){
		$this->db->insert("billsummary", $data);
	}

	function updateBill($collectionId, $billId, $columnName, $value)
	{
		$query = $this->db->query("UPDATE billsummary SET ".$columnName." = '".$value."' WHERE collectionId = '".$collectionId."' AND billId = '".$billId."'");
	}

	function emptyTable($table){
		$this->db->truncate($table);
	}

	function emptyCollection($collectionId) {
		//prep the query
		$this->db->where('collectionId', $collectionId);

		//delete the rows
		$this->db->delete('billsummary');


		//	$this->db->query("DELETE * FROM billsummary WHERE collectionId = ".$collectionId);
	}

	function deleteBillId($billId){
		$this->db->delete("billsummary", array('billId' => $billId));
	}

}

?>