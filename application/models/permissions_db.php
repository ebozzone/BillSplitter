<?php

class Permissions_db extends CI_Model{
	
	function getPermissionsForUser($username){
		$query = $this->db->query("SELECT collectionId FROM permissions WHERE username = '".$username."'");
		return $query->result();
	}

}

?>