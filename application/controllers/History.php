<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class History extends CI_Controller {
	/*
	 1. index() displays a list of all customers
	 2. add_new() insert new customer in DB
	 3. call edit() to modify a customer
	 */

	public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Customer_model');
        $this->load->model('Purchaser_model');
        $this->load->model('Making_model');
        $this->load->model('History_model');

    }

    public function index()
  	{
  		if($this->session->userdata('logged_in'))
          {
          	$data['title'] = 'Balance list';
          	$data['username'] = $this->session->userdata('logged_in');
          	$data['ledger_list'] = $this->History_model->get_customer_ledger();
          	$data['history'] = $this->History_model->get_all_history();
          	$data['custList'] = $this->Customer_model->get_customers();
  	        $this->load->view('layout/header', $data);
  	        $this->load->view('layout/menubar');
      			$this->load->view('historyList', $data);
      			$this->load->view('layout/footer');
  		}
  		else
  		{
  			redirect('Welcome');
  		}
  	}

}
