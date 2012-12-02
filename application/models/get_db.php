<?php

class Get_db extends CI_Model{
	function getAll(){
		$query = $this->db->query("SELECT * FROM billsummary");
		return $query->result();
	}

	function insertNewBill($data){
		$this->db->insert("billsummary", $data);
	}

	function emptyTable($table){
		$this->db->truncate($table);
	}

	function deleteBillId($billId){
		$this->db->delete("billsummary", array('billId' => $billId));
	}

}

?>