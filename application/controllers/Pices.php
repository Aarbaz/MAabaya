<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pices extends CI_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('Pices_model');
		$this->load->model('Stock_model');
		$this->load->model('Product_model');
		$this->load->library('form_validation');
		$this->load->library('tcpdf');
        $this->load->model('Challan_model'); 
		$this->load->library('upload');
		//$this->load->helper('pdf_helper');
		$this->load->helper('url'); 
	}

    public function index(){
		if ($this->session->userdata('logged_in')) {
			$data['title'] = ucfirst('PCs Recive');
			$data['username'] = $this->session->userdata('logged_in');
			$data['products'] = $this->Pices_model->get_products_in_pcs();

			$this->load->view('layout/header', $data);
			$this->load->view('layout/menubar');
			$this->load->view('product_in_pc.php', $data);
			$this->load->view('layout/footer');
		} else {
			redirect('Welcome');
		}
	}

	public function add_new()
	{

		$this->form_validation->set_rules('master_name', 'Master Name', 'trim|required');

		if ($this->input->post('add_product') != NULL) {
			if ($this->form_validation->run() == false) {
				$data['title'] = ucwords('Add new Page');
				$data['username'] = $this->session->userdata('logged_in');
				$data['purList'] = $this->Customer_model->get_customers();
				
				$this->load->view('layout/header', $data);
				$this->load->view('layout/menubar');
				$this->load->view('pices_add', $data);
				$this->load->view('layout/footer');
			} else {
				// POST data
				$postData = $this->input->post();
				$data = array(
					'product_name' => strtoupper($postData['material_name']),
					'owner_name' => strtoupper($postData['owner_name']),
					// 'purchaser_id' => $postData['purchaserID'],
					'price' => $postData['p_price'],
					// 'design_number' => $postData['p_design_number'],
					'stock' => $postData['stock_q'],
					// 'prod_exp' => $postData['product_exp'],
					'total_amount' => $postData['price_total'],
					// 'pcs' => $postData['pcs'],
				);

				$insert = $this->Product_model->add_product($data);
				$product_id = $this->db->insert_id();
				$data2 = array(
					'product_id' => $product_id,
					'stock_qty' => $postData['stock_q'],
					'purchase_rate' => $postData['p_price'],
					// 'p_design_number' => $postData['p_design_number'],
				);
				$Store = $this->Stock_model->add_record($data2);

				if ($insert > 0) {
					$this->session->set_flashdata('success', 'Material added successfully.');
					redirect('Product');
				} else {
					$this->session->set_flashdata('failed', 'Some problem occurred, please try again.');
					$this->load->view('layout/header', $data);
					$data['purList'] = $this->Product_model->get_all_purchaser();
					$this->load->view('layout/menubar');
					
					$this->load->view('product_add', $data);
					$this->load->view('layout/footer');
				}
			}
		} elseif ($this->session->userdata('logged_in')) {
			$data['title'] = ucwords('Add new Material Page');
			$data['username'] = $this->session->userdata('logged_in');
			$data['purList'] = $this->Product_model->get_all_purchaser();
			$data['custList'] = $this->Challan_model->get_all_customer();
        	$data['productList'] = $this->Challan_model->get_all_products();
        	$data['last_invoice'] = $this->Challan_model->get_last_invoice_insider();
			$this->load->view('layout/header', $data);
			$this->load->view('layout/menubar');
			$this->load->view('pices_add', $data);
			$this->load->view('layout/footer');
		} else {
			redirect('Welcome');
		}
	}
}
?>