<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends CI_Controller {

	public function index() {
		$this->home();
	}

	public function home(){
		$data['title'] = "Bill Splitter App!";
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
		$data['results'] = $this->get_db->getAll();
		$this->load->view("view_home", $data);
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
		$this->home();
	}

	function emptyBill(){
		$this->load->model("get_db");

		$tableToEmpty = "billsummary";

		$this->get_db->emptyTable($tableToEmpty);
		$this->home();
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

	function deleteItem(){
		$billId = $this->input->post('rowId');
		$this->load->model("get_db");
		$this->get_db->deleteBillId($billId);
		$this->home();

	}


	}
?>