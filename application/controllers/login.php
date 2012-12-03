<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Not Jorge Torres
 * Description: Login controller class
 */
class Login extends CI_Controller{
	
	function __construct(){
		parent::__construct();
	}
	
	public function index($msg = NULL){
		// Load our view to be displayed
		// to the user
		$data['msg'] = $msg;
		$this->load->view('login_view', $data);
	}
	
	function process(){
		// Load the model
		$this->load->model('login_model');
		// Validate the user can login
		$result = $this->login_model->validate();
		// Now we verify the result
		if(! $result){
			// If user did not validate, then show them login page again
			$msg = '<font color=red>Invalid username and/or password.</font><br />';
			$this->index($msg);
		}else{
			// If user did validate, 
			// Send them to members area
			redirect('site');
		}		
	}

	function createAccount($msg = NULL){
		$data['msg'] = $msg;
		$this->load->view('create_account_view', $data);
	}

	function processCreateAccount(){
		$this->load->model('login_model');
		$this->load->model('permissions_db');
		$message = NULL;

		$username = $this->security->xss_clean($this->input->post('username'));
		$password = $this->security->xss_clean($this->input->post('password'));
		$confirmation = $this->security->xss_clean($this->input->post('confirmation'));

		if (!$this->login_model->validateUserName($username)){
			$message = '<font color=red>User name is taken.</font></br>';
		} 
		else if (strlen($password) < 5) {
			$message = '<font color=red>Password must be greater than 5 letters.</font></br>';
		}
		else if ($password != $confirmation){
			$message = '<font color=red>Password and confirmation must match.</font></br>';
		}
		else {
			//success! add username and password to database
			$this->login_model->addUserEntry($username, $password);

			// TODO - currently hardcoding $newCollectionId for new users
			$newCollectionId = 5;

			//create a first collection for this user
			$this->permissions_db->addCollectionIdPermissionForUser($newCollectionId, $username);

			//redirect to site
			redirect('site');
		}

		//$data['msg'] = $message;
		//$this->load->view('create_account_view', $data);
		$this->createAccount($message);
	}
}
?>