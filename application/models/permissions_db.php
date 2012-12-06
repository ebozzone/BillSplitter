<?php

class Permissions_db extends CI_Model{
	
	function getPermissionsForUser($username){
		$query = $this->db->query("SELECT collectionId FROM permissions WHERE username = '".$username."'");
		return $query->result();
	}

	function addCollectionIdPermissionForUser($collectionId, $username){
		$data = array(
				'username' => $username,
				'collectionId' => $collectionId
			);
		$this->db->insert("permissions", $data);
	}

	function removePermissionForUser($collectionId, $username){
		$this->db->delete("permissions", array('collectionId' => $collectionId, 'username' => $username));
	}

	//return an array of usernames that have access to a certain collectionId
	function getUsersWithPermission($collectionId){
		$query = $this->db->query("SELECT username FROM permissions WHERE collectionId = '".$collectionId."'");
		return $query->result();
	}

}

?>