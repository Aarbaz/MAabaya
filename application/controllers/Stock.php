<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Stock extends CI_Controller {

	/*
	methods name with call sequence
	1- index() displays ledger balance for all customers and a button to add new balance
	2- ledger() display a form to add ledger balance record customer vise
	3- ledgerBalance() get customer single balane sheet in modal widow
	*/

	public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Product_model');
        $this->load->model('Design_model');
        $this->load->model('Stock_model');
        $this->load->model('Purchaser_model');
        $this->load->model('Making_model');
    }

    public function index()
	{
		if($this->session->userdata('logged_in'))
        {
        	$data['title'] = ucfirst('Product List Page');
        	$data['username'] = $this->session->userdata('logged_in');
        	//$data['products'] = $this->Product_model->get_all_products();
        	$data['products'] = $this->Design_model->get_all_design();
        	$data['stocks'] = $this->Stock_model->get_stock();
        	$data['purchaser_stock'] = $this->Purchaser_model->get_purchaser_stock();
        	$data['maker_stocks'] = $this->Making_model->get_maker_stock();
			/* var_dump($data['stocks']->stock_qty);
			var_dump($data); */
			//var_dump($data['stocks']->stock_qty);
			$this->load->view('layout/header', $data);
	        $this->load->view('layout/menubar');
			$this->load->view('stockList', $data);
			$this->load->view('layout/footer');
		}
		else
		{
			redirect('Welcome');
		}
	}

	//form to add new product
	public function add_new()
	{
		$this->form_validation->set_rules('prod_name', 'Product Name', 'trim|required|callback_regexValidate|is_unique[products.product_name]',  array('is_unique' => 'This %s already exists.'));
		$this->form_validation->set_rules('prod_exp', 'Expiry Date', 'trim|required');
		$this->form_validation->set_rules('weight', 'Product Amount', 'trim|required|numeric');
		$this->form_validation->set_rules('price', 'Product Quantity', 'trim|required|numeric');

		if( $this->input->post('add_product') != NULL )
		{
			if ($this->form_validation->run() == false)
			{
				$data['title'] = ucwords('Add new Product Page');
	        	$data['username'] = $this->session->userdata('logged_in');
		        $this->load->view('layout/header', $data);
		        $this->load->view('layout/menubar');
				$this->load->view('product_add', $data);
				$this->load->view('layout/footer');
			}
			else
			{
				// POST data
     			$postData = $this->input->post();
				$data = array(
					'product_name' => strtoupper($postData['prod_name']),
					'prod_exp' => strtoupper($postData['prod_exp']),
					'weight' => $postData['weight'],
					'unit_price' => $postData['price'],
					'stock_qty' => $postData['stock_q'],
					'price' => $postData['price_total'],
				);

				$insert = $this->Product_model->add_product($data);
				if($insert > 0)
				{
					$this->session->set_flashdata('success', 'Product added successfully.');
					redirect('Product');
				}
				else
				{
					$this->session->set_flashdata('failed', 'Some problem occurred, please try again.');
					$this->load->view('layout/header', $data);
			        $this->load->view('layout/menubar');
					$this->load->view('product_add', $data);
					$this->load->view('layout/footer');
				}
			}
     	}

		elseif($this->session->userdata('logged_in'))
        {
        	$data['title'] = ucwords('Add new Product Page');
        	$data['username'] = $this->session->userdata('logged_in');

	        $this->load->view('layout/header', $data);
	        $this->load->view('layout/menubar');
			$this->load->view('product_add', $data);
			$this->load->view('layout/footer');
		}
		else
		{
			redirect('Welcome');
		}
	}

}
