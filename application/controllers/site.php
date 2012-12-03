<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->check_isvalidated();
    }

	public function index() {
		//$this->collections();
		$this->home();
	}

	public function home(){
		$data['title'] = "Evan's title";
		$data['options'] = array(
                  'select' => 'Select One',
                  'friend1' => 'Evan',
                  'friend2' => 'Manu',
                  'friend3' => 'Jon',
                  'friend4' => 'Dave',
                  'friend5' => 'Mary',
                );
		$this->load->helper('form');
		$this->load->model("get_db");
		$data['results'] = $this->get_db->getAll($this->collectionIdForUser());
		$contributions = $this->calculateContributionRows($data['results']);
		$amountsOwed = $this->calculateAmountsOwed($contributions, $data['results']);
		$amountsPaid = $this->calculateAmountsPaid($data['results']);
		//echo "results:"."</br>";
		//print_r($data['results']);
		//echo "</br>"."contributions:"."</br>";
		//print_r($contributions);
		//echo "</br>"."amounts owed:"."</br>";
		//print_r($amountsOwed);
		//echo "</br>"."amounts paid:"."</br>";
		//print_r($amountsPaid);
		$data['contributions'] = $contributions;
		$data['amountsOwed'] = $amountsOwed;
		$data['amountsPaid'] = $amountsPaid;
		$this->load->view("view_home", $data);
		}

	public function collections(){
		//figure out the collections corresponding to the user

		//load them in an array
		$collections = array('');
		//pass it to the corresponding view

	}

	function addBill(){
		$this->load->model("get_db");

		$item = $this->input->post('item');
		$amount = $this->input->post('amount');
		$payer = $this->input->post('payers');
		$friend1 = $this->input->post('friend1');
		$friend2 = $this->input->post('friend2');
		$friend3 = $this->input->post('friend3');
		$friend4 = $this->input->post('friend4');
		$friend5 = $this->input->post('friend5');


		$newRow = array(
			"collectionId" => $this->collectionIdForUser(),
			"billId" => time(), 
			"item" => $item,//"Car Rental", 
			"amount" => $amount,//"250", 
			"name" => $this->tempNameForFriend($payer),//, 
			"friend1" => $friend1,//"TRUE", 
			"friend2" => $friend2,//"TRUE", 
			"friend3" => $friend3,//"TRUE", 
			"friend4" => $friend4,//"TRUE", 
			"friend5" => $friend5//"TRUE"
		);
		$this->get_db->insertNewBill($newRow);
		//$this->home();
		redirect('site');
	}

	function emptyBill(){
		$this->load->model("get_db");

		//$tableToEmpty = "billsummary";

		//$this->get_db->emptyTable($tableToEmpty);
		//$this->home();
		$this->get_db->emptyCollection($this->collectionIdForUser());

		redirect('site');
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
		redirect('site');

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

    function collectionIdForUser(){
    	$this->load->model("permissions_db");

    	$username = $this->session->userdata('username');
    	$result = $this->permissions_db->getPermissionsForUser($username);
    	return $result[0]->collectionId;
    }

    function generateNewCollectionId(){
    	// Generates a new collection ID using some algorithm, checks to make sure it hasn't been taken already

    }


	}
?>