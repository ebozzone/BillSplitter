<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Someone besides Jorge Torres
 * Description: Login model class
 */
class Login_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	public function validate(){
		// grab user input
		$username = $this->security->xss_clean($this->input->post('username'));
		$password = $this->security->xss_clean($this->input->post('password'));
		
		// Prep the query
		$this->db->where('username', $username);
		$this->db->where('password', $password);
		
		// Run the query
		$query = $this->db->get('users');
		// Let's check if there are any results
		if($query->num_rows == 1)
		{
			// If there is a user, then create session data
			$row = $query->row();
			$data = array(
					'userid' => $row->userid,
					'fname' => $row->fname,
					'lname' => $row->lname,
					'username' => $row->username,
					'validated' => true
					);
			$this->session->set_userdata($data);
			return true;
		}
		// If the previous process did not validate
		// then return false.
		return false;
	}

	public function validateUserName($username){
		//prep the query
		$this->db->where('username', $username);

		//run the query
		$query = $this->db->get('users');

		//let's check the results
		if($query->num_rows == 0){
			return true;
		}
		else return false;
	}

	public function addUserEntry($username, $password){
		$data = array(
				'username' => $username,
				'fname' => 'John',
				'lname' => 'Doe',
				'password' => $password
			);
		$this->db->insert("users", $data);
	}
}
?>