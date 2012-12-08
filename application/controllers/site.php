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

	public function home(){
		$this->load->helper('url');

		//put name of collection into session data
    	$this->load->model("permissions_db");
    	$this->load->model("collectionnames_db");
    	$collectionData = $this->collectionnames_db->getCollectionData($this->session->userdata('collectionId'));
    	$this->session->set_userdata('collectionName', $collectionData[0]['collectionName']);
    	$this->session->set_userdata('collectionData', $collectionData);

		$data['title'] = "BillSplitter Collection";
//		$data['options'] = array(
//			'select' => 'Select One',
//			'friend1' => 'Evan',
//			'friend2' => 'Manu',
//			'friend3' => 'Jon',
//			'friend4' => 'Dave',
//			'friend5' => 'Mary',
//			);
		
		//load friend names into 'options'
		$numFriends = $collectionData[0]['friendCount'];

		$data['friends'] = array();
		for($i = 1; $i < $numFriends + 1; $i++){
			$friendName = 'friend'.$i;
			$data['friends'][$friendName] = $collectionData[0]['friend'.$i];
		}
		$data['numFriends'] = $numFriends;
		$this->load->helper('form');
		$this->load->model("get_db");
		//load table of bills for user into an array
		//$data['results'] = $this->get_db->getAll($this->collectionIdForUser());
		$data['results'] = $this->get_db->getAll($this->session->userdata('collectionId'));
		//do the math on who paid, and is owed
		$data['contributions'] = $this->calculateContributionRows($data['results']);
		$data['amountsOwed'] = $this->calculateAmountsOwed($data['contributions'], $data['results']);
		$data['amountsPaid'] = $this->calculateAmountsPaid($data['results']);
		//echo "results:"."</br>";
		//print_r($data['results']);
		//echo "</br>"."contributions:"."</br>";
		//print_r($contributions);
		//echo "</br>"."amounts owed:"."</br>";
		//print_r($amountsOwed);
		//echo "</br>"."amounts paid:"."</br>";
		//print_r($amountsPaid);
		$this->load->view("view_home", $data);
		}

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
			"name" => $payer,//$sessionCollectionData[0][$payer],//$this->tempNameForFriend($payer),//, 
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

	function addColumn(){
		$name = $this->input->post('newColumnName');
		$this->load->model('collectionnames_db');
		$this->collectionnames_db->addColumnToCollection($this->session->userdata('collectionId'), $name);
		redirect('site/home');
	}

	function deleteColumn(){
		$friendIndexToDelete = $this->input->post('friendToDelete');
		$this->load->model('collectionnames_db');
		//$friendIndexToDelete++;
		$this->collectionnames_db->deleteFriendColumnFromCollection($this->session->userdata('collectionId'), $friendIndexToDelete);
		redirect('site/home');	
	}

	function emptyBill(){
		$this->load->model("get_db");

		//$tableToEmpty = "billsummary";

		//$this->get_db->emptyTable($tableToEmpty);
		//$this->home();
		$this->get_db->emptyCollection($this->session->userdata('collectionId'));
		$this->home();
		//redirect('site');
	}

	function tempNameForFriend($friendID){
		$friendName;
		switch ($friendID) {
			case 'friend1':
				$friendName = "Evan";
				break;
			case 'friend2':
				$friendName = "Manu";
				break;
			case 'friend3':
				$friendName = "Jon";
				break;
			case 'friend4':
				$friendName = "Dave";
				break;
			case 'friend5':
				$friendName = "Mary";
				break;
			default:
				$friendName = 'Rutherford B. Hayes';
		}

		return $friendName;
	}

	function tempFriendIdForName($friendName){
		$friendID;
		switch ($friendName) {
			case 'Evan':
				$friendID = "friend1";
				break;
			case 'Manu':
				$friendID = "friend2";
				break;
			case 'Jon':
				$friendID = "friend3";
				break;
			case 'Dave':
				$friendID = "friend4";
				break;
			case 'Mary':
				$friendID = "friend5";
				break;
			default:
				$friendID = 'friend1';
		}

		return $friendID;
	}

	function deleteItem(){
		$billId = $this->input->post('rowId');
		$this->load->model("get_db");
		$this->get_db->deleteBillId($billId);
		//$this->home();
		redirect('site/home');

	}

	function calculateContributionRows($resultsData) {
		$contributions = array();
		foreach($resultsData as $index=>$row) {
			$contributionsRow = array();
			$numfriends = $row->friend1 + $row->friend2 + $row->friend3 + $row->friend4 + $row->friend5;
			$itemCost = $row->amount;
			$individualContribution = $itemCost / $numfriends;
			$contributionsRow['friend1'] = 0;
			$contributionsRow['friend2'] = 0;
			$contributionsRow['friend3'] = 0;
			$contributionsRow['friend4'] = 0;
			$contributionsRow['friend5'] = 0;
			if ($row->friend1 == 1) $contributionsRow['friend1'] = $individualContribution;
			if ($row->friend2 == 1) $contributionsRow['friend2'] = $individualContribution; 
			if ($row->friend3 == 1) $contributionsRow['friend3'] = $individualContribution; 
			if ($row->friend4 == 1) $contributionsRow['friend4'] = $individualContribution; 
			if ($row->friend5 == 1) $contributionsRow['friend5'] = $individualContribution;   
			$contributions[$index] = $contributionsRow;
		}
		return $contributions;
	}

	function calculateAmountsOwed($contributions, $results){
		$amountsOwed = array();

		//initialize at zero for each friend
		for($i = 1; $i < 6; $i++){
			$amountsOwed['friend'.$i] = 0;
		}		

		//go through each row and add that friend's amount to his running tally
		foreach($contributions as $contributionRow){
			//for each friend
			for($j = 1; $j < 6; $j++){
				$amountsOwed['friend'.$j] = $amountsOwed['friend'.$j] + $contributionRow['friend'.$j];
			}
		
		}
		//return value
		return $amountsOwed;
	}

	function calculateAmountsPaid($results){
		$amountsPaid = array();

		//initialize at zero for each friend
		for($i = 1; $i < 6; $i++){
			$amountsPaid['friend'.$i] = 0;
		}

		//go through each entry in the bill and assign it to the right payer
		foreach($results as $resultsRow){
			$payer = $resultsRow->name;
			$amountsPaid[$this->tempFriendIdForName($payer)] = $amountsPaid[$this->tempFriendIdForName($payer)] + $resultsRow->amount; 
		}

		//return the sums
		return $amountsPaid;

	}

	//Login Related Stuff

	private function check_isvalidated(){
        if(! $this->session->userdata('validated')){
            redirect('login');
        }
    }

    public function do_logout(){
    	$this->session->sess_destroy();
    	redirect('login');
    }

    //Collection Related Stuff

//    function collectionIdForUser(){
//    	$this->load->model("permissions_db");
//    	$username = $this->session->userdata('username');
//    	$result = $this->permissions_db->getPermissionsForUser($username);
//    	// TODO - need to pull more than one collectionId per user
//    	return $result[0]->collectionId;
//    }

    // Returns all collectionId's associated with the logged in username
    function collectionPermissionsForUser(){
    	$this->load->model("permissions_db");
    	$username = $this->session->userdata('username');
    	$result = $this->permissions_db->getPermissionsForUser($username);
    	return $result;
    }

    function linkCollection(){
    	$this->session->set_userdata('collectionId', $this->input->get('collectionId'));
    	$this->home();
    }

    function linkPermissions(){
    	$this->session->set_userdata('collectionId', $this->input->get('collectionId'));
    	$this->permissionsList();
    }

    //Collection Management

	function createNewCollectionForUser(){
		$this->load->library('collectionIdManager');
		$this->load->model('permissions_db');
		$this->load->model('collectionnames_db');
		$newCollectionId = $this->collectionidmanager->generateNewCollectionId();
		$this->permissions_db->addCollectionIdPermissionForUser($newCollectionId, $this->session->userdata('username')	);
		$this->collectionnames_db->newCollectionName($newCollectionId);
		$this->session->set_userdata('collectionId', $newCollectionId);
		$this->home();
	}

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

	function addPermissions2(){
		echo "Awesome, this thing worked.";
	}

	function removePermission(){
		$username = $this->input->post('userToRemove');
		$this->load->model("permissions_db");
		$this->permissions_db->removePermissionForUser($this->session->userdata('collectionId'), $username);
		//$this->home();
		redirect('site/permissionsList');	
	}

	}
?>