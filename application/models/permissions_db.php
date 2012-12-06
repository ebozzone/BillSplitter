<?php

class Permissions_db extends CI_Model{
	
	function getPermissionsForUser($username){
		$query = $this->db->query("SELECT permissions.*, collectionnames.* FROM permissions, collectionnames WHERE permissions.username = '".$username."' AND permissions.collectionId = collectionnames.collectionId");
		return $query->result();
	}

	function newCollectionName($collectionId){
		$data = array(
				'collectionId' => $collectionId,
				'collectionName' => "New Collection"
			);
		$this->db->insert("collectionnames", $data);
	}

	function getCollectionName($collectionId){
		$query = $this->db->query("SELECT collectionName FROM collectionnames WHERE collectionId = '".$collectionId."'");
		return $query->result_array();
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