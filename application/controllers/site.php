<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends CI_Controller {

	function __construct(){
        parent::__construct();
        //$this->check_isvalidated();
    }

	public function index() {
		$this->collectionsList();
		//$this->home();
	}

	//a request to home opens the main website page, a table showing a single collection of expenses
	public function home(){
		$this->load->helper('url');

		//put name of collection into session data
    	$this->load->model("permissions_db");
    	$this->load->model("collectionnames_db");
    	$collectionData = $this->collectionnames_db->getCollectionData($this->session->userdata('collectionId'));
    	$this->session->set_userdata('collectionName', $collectionData[0]['collectionName']);
    	$this->session->set_userdata('collectionData', $collectionData);

		$data['title'] = "BillSplit.it";

		$numFriends = $collectionData[0]['friendCount'];

		//calculate the number of friends, include this information for easy access by the view
		$data['friends'] = array();
		for($i = 1; $i < $numFriends + 1; $i++){
			$friendName = 'friend'.$i;
			$data['friends'][$friendName] = $collectionData[0]['friend'.$i];
		}
		$data['numFriends'] = $numFriends;
		$this->load->helper('form');
		$this->load->model("get_db");

		//load table of bills for user into an array
		$data['results'] = $this->get_db->getAll($this->session->userdata('collectionId'));

		//pass this to the view
		$this->load->view("view_home", $data);
	}

	//responds to an ajax post request from the client and provides data to populate a collection table
	public function loadTable(){
		$this->load->helper('url');
		
		//put name of collection into session data
    	$this->load->model("permissions_db");
    	$this->load->model("collectionnames_db");
    	$collectionData = $this->collectionnames_db->getCollectionData($this->session->userdata('collectionId'));
    	$this->session->set_userdata('collectionName', $collectionData[0]['collectionName']);
    	$this->session->set_userdata('collectionData', $collectionData);

		$data['title'] = "BillSplit.it Collection";
		
		//load friend names into 'options'
		$numFriends = $collectionData[0]['friendCount']; // refers to the single row containing metadata about the collection

		//calculate number of friends for easy access
		$data['friends'] = array();
		for($i = 1; $i < $numFriends + 1; $i++){
			$friendName = 'friend'.$i;
			$data['friends'][$friendName] = $collectionData[0]['friend'.$i];
		}
		$data['numFriends'] = $numFriends;
		$this->load->helper('form');
		$this->load->model("get_db");
		//load table of bills for user into an array
		
		$data['results'] = $this->get_db->getAll($this->session->userdata('collectionId'));

		//encode this information in json format, print it to the screen for the client to read / parse
		print_r (json_encode($data));
	}

	//a request made to site/collectionsList produces the view_collections page 
	public function collectionsList(){
		$data['title'] = "BillSplitter Dashboard";
		//load the collections corresponding to the user
		$data['collections'] = $this->collectionPermissionsForUser();
		
		//load codeigniter helpers
		$this->load->helper('form');

		//pass it to the corresponding view
		$this->load->view("view_collections", $data);
	}

	//links to "Edit Permissions" page
	public function permissionsList(){
		$data['title'] = "Edit Permissions";
		
		//load the users corresponding to the collectionId
		$this->load->model("permissions_db");
    	$data['permissions'] = $this->permissions_db->getUsersWithPermission($this->session->userdata('collectionId'));

		//load codeigniter helpers
		$this->load->helper('form');

		//pass it to the corresponding view
		$this->load->view("view_permissions", $data);
	}

	//a post request made to site/addBill (through ajax) posts an additional item expense entry for a given collection
	function addBill(){
		$this->load->model("get_db");
		//$this->load->model("collectionnames_db");

		$item = $this->input->post('item');
		$amount = $this->input->post('amount');
		$payer = $this->input->post('payers');
		$friend1 = $this->input->post('friend1');
		$friend2 = $this->input->post('friend2');
		$friend3 = $this->input->post('friend3');
		$friend4 = $this->input->post('friend4');
		$friend5 = $this->input->post('friend5');
		$friend6 = $this->input->post('friend6');
		$friend7 = $this->input->post('friend7');
		$friend8 = $this->input->post('friend8');
		$friend9 = $this->input->post('friend9');
		$friend10 = $this->input->post('friend10');
		$friend11 = $this->input->post('friend11');
		$friend12 = $this->input->post('friend12');
		$friend13 = $this->input->post('friend13');
		$friend14 = $this->input->post('friend14');
		$friend15 = $this->input->post('friend15');

		$sessionCollectionData = $this->session->userdata('collectionData');

		$newRow = array(
			"collectionId" => $this->session->userdata('collectionId'),
			"billId" => time(), 
			"item" => $item,//"Car Rental", 
			"amount" => $amount,//"250", 
			"name" => $payer,
			"friend1" => $friend1, 
			"friend2" => $friend2,
			"friend3" => $friend3,
			"friend4" => $friend4,
			"friend5" => $friend5,
			"friend6" => $friend6,
			"friend7" => $friend7,
			"friend8" => $friend8,
			"friend9" => $friend9,
			"friend10" => $friend10,
			"friend11" => $friend11,
			"friend12" => $friend12,
			"friend13" => $friend13,
			"friend14" => $friend14,
			"friend15" => $friend15
		);
		$this->get_db->insertNewBill($newRow);
		//$this->home();
		redirect('site/home');
	}

	//a post request made here adds a new friend column into the database for a given collection
	function addColumn(){
		$name = $this->input->post('newColumnName');
		$this->load->model('collectionnames_db');
		$this->collectionnames_db->addColumnToCollection($this->session->userdata('collectionId'), $name);
		redirect('site/home');
	}

	//a post request made here removes a given column (given by int 'friendToDelete') for a given collection 
	function deleteColumn(){
		$friendIndexToDelete = $this->input->post('friendToDelete');
		$this->load->model('collectionnames_db');
		$this->collectionnames_db->deleteFriendColumnFromCollection($this->session->userdata('collectionId'), $friendIndexToDelete);
		redirect('site/home');	
	}

	//a post request made here prompts a database call to change a friend (column header) name
 	function updateFriendName(){
		$this->load->model('collectionnames_db');
		$index = $this->input->post('friendId');
		$newName = $this->input->post('newName');
		$index++;
		echo $newName;
		echo $index;
		$this->collectionnames_db->updateFriendName($this->session->userdata('collectionId'), $index, $newName);
	}

	//a post request made here removes all items from a collection. was used for debugging but we're keeping it in case.
	function emptyBill(){
		$this->load->model("get_db");

		$this->get_db->emptyCollection($this->session->userdata('collectionId'));
		$this->home();
	}

	//a post request made here prompts a request to the model to remove an item
	function deleteItem(){
		$billId = $this->input->post('rowId');
		$this->load->model("get_db");
		$this->get_db->deleteBillId($billId);
		//$this->home();
		redirect('site/home');

	}

	//a post request made here prompts a request to the model to update an item
	function updateItem(){
		//we get the item from the post
		$billId = $this->input->post('billId');
		$newItem = $this->input->post('newItem');

		//load database
		$this->load->model("get_db");

		//make database update of item
		$this->get_db->updateBill($this->session->userdata('collectionId'), $billId, 'item', $newItem);
	}

	//a post request made here prompts a request to the model to update a dollar amount
	function updateAmount(){
		//we get the item from the post
		$billId = $this->input->post('billId');
		$newAmount = $this->input->post('newAmount');

		//load database
		$this->load->model("get_db");

		//make database update of item
		$this->get_db->updateBill($this->session->userdata('collectionId'), $billId, 'amount', $newAmount);
	}

	//a post request made here prompts a request to the model to update a payer name
	function updatePayer(){
		//we get the item from the post
		$billId = $this->input->post('billId');
		$newPayer = $this->input->post('newPayer');

		//load database
		$this->load->model("get_db");

		//make database update of item
		$this->get_db->updateBill($this->session->userdata('collectionId'), $billId, 'name', $newPayer);
	}

	//a post request made here prompts a request to the model to update checkbox value
	function updateCheckbox(){
		//we get the item from the post
		$billId = $this->input->post('billId');
		$newCheck = $this->input->post('newCheck');
		$friendId = $this->input->post('friendId');
		$friendId++;
		$columnName = "friend".$friendId;
		echo $columnName;

		//load database
		$this->load->model("get_db");

		//make database update of item
		$this->get_db->updateBill($this->session->userdata('collectionId'), $billId, $columnName, $newCheck);
	}

	//a post request made here prompts a request to the model to change the name of a collection
	function changeCollectionName() {
		//get the new collection name
		$this->load->helper('text');
		$newCollectionName = $this->input->post('newCollectionName');
		$newCollectionName = ascii_to_entities($newCollectionName);
		$this->load->model('collectionnames_db');

		//prompt the model to update
		$this->collectionnames_db->updateCollectionName($this->session->userdata('collectionId'), $newCollectionName);
		$collectionData = $this->collectionnames_db->getCollectionData($this->session->userdata('collectionId'));
    	echo $collectionData[0]['collectionName'];
	}

	//a request made here opens a page with the name of the current collection and nothing else. for reference for the view.
	function collectionName() {
		$this->load->model("collectionnames_db");
    	$collectionData = $this->collectionnames_db->getCollectionData($this->session->userdata('collectionId'));
    	echo $collectionData[0]['collectionName'];
	}

	//Login Related Stuff

	//checks if a particular user is validated
	private function check_isvalidated(){
        if(! $this->session->userdata('validated')){
            redirect('login');
        }
    }

	//logs a given user out by destroying their session
    public function do_logout(){
    	$this->session->sess_destroy();
    	redirect('login');
    }

    //Collection Related Stuff

    // Returns all collectionId's associated with the logged in username
    function collectionPermissionsForUser(){
    	$this->load->model("permissions_db");
    	$username = $this->session->userdata('username');
    	$result = $this->permissions_db->getPermissionsForUser($username);
    	return $result;
    }

	//stores a collection Id in the user's session data
    function linkCollection(){
    	$this->session->set_userdata('collectionId', $this->input->get('collectionId'));
    	$this->home();
    }

   	//stores a user's collection ID in the user's session data. links to permissions list
    function linkPermissions(){
    	$this->session->set_userdata('collectionId', $this->input->get('collectionId'));
    	$this->permissionsList();
    }

    //Collection Management

	//creates a new collection for a user
	function createNewCollectionForUser(){
		//load models
		$this->load->library('collectionIdManager');
		$this->load->model('permissions_db');
		$this->load->model('collectionnames_db');
		
		//generate the new collection id, connect it to the user
		$newCollectionId = $this->collectionidmanager->generateNewCollectionId();
		$this->permissions_db->addCollectionIdPermissionForUser($newCollectionId, $this->session->userdata('username')	);
		$this->collectionnames_db->newCollectionName($newCollectionId);
		$this->session->set_userdata('collectionId', $newCollectionId);
		redirect('site/home');
	}

	//removes the user from permissions for a collection. does not destroy the collection
	function removeCollection(){
		$collectionId = $this->input->post('collectionId');
		$this->load->model("permissions_db");
		$this->permissions_db->removePermissionForUser($collectionId, $this->session->userdata('username'));
		//$this->home();
		redirect('site/collectionsList');	
	}		

	//Adds input email addresses to permissions table for given collectionId and sends invitation emails
	function addPermissions(){
		$origin = $this->input->post('origin');
		$collectionId = $this->input->post('collectionId');
		$emails_csv = $this->input->post('emails');
		$emails_array = str_getcsv($emails_csv); //create array
		$emails_array = array_filter(array_map('trim', $emails_array)); //trims off white space from entries
		$emails_array = array_unique($emails_array); //removes duplicates
		$emails_array_valid = array();
		$emails_array_invalid = array();
		//validate each email address
		foreach($emails_array as $email){
			if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
	        	// valid address
   				$emails_array_valid[] = $email;
   			}
    		else {
        		// invalid address
    			$emails_array_invalid[] = $email;
    		}
		}
		//enter invalid emails into session flashdata to be displayed
		$this->session->set_flashdata('emails_array_invalid',$emails_array_invalid);
		//add permissions for valid emails
		$this->load->model('permissions_db');
		foreach($emails_array_valid as $row){
			$this->permissions_db->addCollectionIdPermissionForUser($collectionId, $row);
		}
		redirect('site/' . $origin);
	}

	//easter egg
	function addPermissions2(){
		echo "Awesome, this thing worked.";
	}

	//removes permission for another user
	function removePermission(){
		$username = $this->input->post('userToRemove');
		$this->load->model("permissions_db");
		$this->permissions_db->removePermissionForUser($this->session->userdata('collectionId'), $username);
		//$this->home();
		redirect('site/permissionsList');	
	}

	}
?>