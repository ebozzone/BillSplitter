<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Jorge Torres supplied some template code for login functionality
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

		//if we're already logged in, send to collections view; otherwise, load the login view

		if ($this->session->userdata('username')) {
			redirect('site');
		}
		else {
			$this->load->view('login_view', $data);	
		}
		
	}
	
	function process($username = NULL){
		// Load the models
		$this->load->model('login_model');
		$this->load->model('permissions_db');
		$this->load->model('collectionnames_db');
		// Validate the user can login
		if ($username == NULL) 
		{
				$username = $this->input->post('username');
		}
		$result = $this->login_model->validate();
		$existingCollectionId = $this->session->userdata('collectionId');
		// Now we verify the result
		if(! $result){
			// If user did not validate, then show them login page again
			$msg = '<font color=red>Invalid username and/or password.</font><br />';
			$this->index($msg);
		}else{
			
		//check if user creating account came from homepage or is saving an existing collection
		if($existingCollectionId != NULL){
			//assign existing collectionId to new user and create collection name
			$this->permissions_db->addCollectionIdPermissionForUser($existingCollectionId, $username);	
			
		}

			// If user did validate, 
			// Send them to members area
			redirect('site');
		}		
	}

	function createAccount($msg = NULL){
		$data['msg'] = $msg;
		$this->load->view('create_account_view', $data);
	}

	function forgotPassword($msg = NULL){
		$data['msg'] = $msg;
		$this->load->view('view_forgot_password', $data);
	}

	function processCreateAccount(){
		$this->load->model('login_model');
		$this->load->model('permissions_db');
		$this->load->model('collectionnames_db');
		$message = NULL;
		//get username and password from form post
		$username = $this->security->xss_clean($this->input->post('username'));
		$firstname = $this->security->xss_clean($this->input->post('fname'));
		$password = $this->security->xss_clean($this->input->post('password'));
		$confirmation = $this->security->xss_clean($this->input->post('confirmation'));
		$existingCollectionId = $this->session->userdata('collectionId');

		if ($this->login_model->validateUserName($username)){
			$message = '<font color=red>User name is taken.</font></br>';
		} 
		else if (!filter_var($username, FILTER_VALIDATE_EMAIL)){
			$message = '<font color=red>Invalid email address.</font></br>';
		}
		else if (strlen($password) < 5) {
			$message = '<font color=red>Password must be greater than 5 letters.</font></br>';
		}
		else if (strlen($firstname) < 1) {
			$message = '<font color=red>Please enter a first name.</font></br>';
		}
		else if ($password != $confirmation){
			$message = '<font color=red>Password and confirmation must match.</font></br>';
		}
		else {
			//success! add username and password to database
			$this->login_model->addUserEntry($username, $password, $firstname);

			//check if user creating account came from homepage or is saving an existing collection
			if($existingCollectionId != NULL){
				//assign existing collectionId to new user and create collection name
				//$this->permissions_db->addCollectionIdPermissionForUser($existingCollectionId, $username);	

			}
			else{
				//create a first collection for this user
				$this->load->library('collectionIdManager');
				$newCollectionId = $this->collectionidmanager->generateNewCollectionId();
				$this->collectionnames_db->newCollectionName($newCollectionId);
				$this->permissions_db->addCollectionIdPermissionForUser($newCollectionId, $username);	
			}
			

			//log the user in with their newly created credentials (the post from create_acount_view will pass on to the process() method)
			$this->process($username);
		}

		//$data['msg'] = $message;
		//$this->load->view('create_account_view', $data);
		$this->createAccount($message);
	}



	function processForgotPassword(){
		$this->load->model('login_model');
		$this->load->model('permissions_db');
		$message = NULL;
		//get username from form post
		$username = $this->security->xss_clean($this->input->post('username'));
		
		if (!$this->login_model->validateUserName($username)){
			$message = '<font color=red>User name not found.</font><br/>';
			$this->forgotPassword($message);
		}
		else {
			//success! email the password to the email address
			$message = '<font color=green>Password sent.</font><br/>';
			$this->emailForgotPassword($username, $message);
		}
			
		
	}

	//TODO THIS DOESN'T WORK YET
	function emailForgotPassword($username, $message){
		$this->load->library('email');

		//$this->email->from('billsplittersite@gmail.com');
		$this->email->to('mlakkur@gmail.com'); 
		
		$this->email->subject('Email Test');
		$this->email->message('Testing the email class. Will need to put users password here');	

		if(!$this->email->send()){
			$message = '<font color=red>Password send error.</font><br/>';
		}

		echo $this->email->print_debugger();
		
		$this->forgotPassword($message);

	}

	function createCollectionNoLogin(){
		$this->load->library('collectionIdManager');
		$newCollectionId = $this->collectionidmanager->generateNewCollectionId();
		$this->load->model('permissions_db');
		$this->load->model('collectionnames_db');
		$this->collectionnames_db->newCollectionName($newCollectionId);
		//$this->session->set_userdata('collectionId', $newCollectionId);
		redirect('site/linkCollection?collectionId='.$newCollectionId);
	}

}

?>