<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product extends CI_Controller
{
	/*
	 1. idex() is called to dislay list of materials
	 2. add_new() creates a new entry into DB
	 3. edit() to update product
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('Product_model');
		$this->load->model('Stock_model');
	}

	public function regexValidate($str)
	{
		if (!preg_match('/^[a-zA-Z0-9\s\.]+$/', $str)) {
			$this->form_validation->set_message('regexValidate', 'The %s field must only contain letters and/or number');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	public function index()
	{
		if ($this->session->userdata('logged_in')) {
			$data['title'] = ucfirst('Material List Page');
			$data['username'] = $this->session->userdata('logged_in');
			$data['products'] = $this->Product_model->get_all_products();

			$this->load->view('layout/header', $data);
			$this->load->view('layout/menubar');
			$this->load->view('productList', $data);
			$this->load->view('layout/footer');
		} else {
			redirect('Welcome');
		}
	}
	// get from DB
	public function list_a_product($id)
	{
		$materials = $this->Product_model->get_product_byID($id);
		echo json_encode($materials);
	}

	//form to add new product
	public function add_new()
	{

		// $this->form_validation->set_rules('prod_name', 'Product Name', 'trim|required|callback_regexValidate|is_unique[products.product_name]',  array('is_unique' => 'This %s already exists.'));
		// $this->form_validation->set_rules('material_name', 'Material Name', 'trim|required');
		$this->form_validation->set_rules('owner_name', 'Owner Name', 'trim|required');
		// $this->form_validation->set_rules('p_price', 'Product Amount', 'trim|required|numeric');
		// $this->form_validation->set_rules('stock_q', 'Product Amount', 'trim|required|numeric');
		// $this->form_validation->set_rules('price_total', 'Product Quantity', 'trim|required|numeric');

		if ($this->input->post('add_product') != NULL) {
			if ($this->form_validation->run() == false) {
				$data['title'] = ucwords('Add new Product Page');
				$data['username'] = $this->session->userdata('logged_in');
				$data['purList'] = $this->Product_model->get_all_purchaser();
				$this->load->view('layout/header', $data);
				$this->load->view('layout/menubar');
				$this->load->view('product_add', $data);
				$this->load->view('layout/footer');
				// echo "strs8";
				// print_r($this->input->post());

			} else {
				// echo "strs2";
				// POST data
				$postData = $this->input->post();
				$material_name = implode(',', $this->input->post('material_name[]'));
				$material_name = trim($material_name, ',');

				$qnty = implode(',', $this->input->post('stock_q[]'));
				$qnty = trim($qnty, ',');

				$rate = implode(',', $this->input->post('p_price[]'));
				$rate = trim($rate, ',');

				$amount = implode(',', $this->input->post('price_total[]'));
				$amount = trim($amount,',');

				$data = array(
					'owner_name' => strtoupper($postData['owner_name']),
					'product_name' => $material_name,
					'price' => $rate,
					'stock' => $qnty,
					'total_amount' => $amount,
				);
				// print_r($data);
				// die();
				$insert = $this->Product_model->add_product($data);
				$product_id = $this->db->insert_id();
				// $data2 = array(
				// 	'product_id' => $product_id,
				// 	'stock_qty' => $postData['stock_q'],
				// 	'purchase_rate' => $postData['p_price'],
				// 	// 'p_design_number' => $postData['p_design_number'],
				// );
				// $Store = $this->Stock_model->add_record($data2);

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
			$this->load->view('layout/header', $data);
			$this->load->view('layout/menubar');
			$this->load->view('product_add', $data);
			$this->load->view('layout/footer');
		} else {
			redirect('Welcome');
		}
	}

	//form to UPDATE PRODUCT
	public function edit($prod_id)
	{
		$cust_data = $this->Product_model->get_product_byID($prod_id);
		if (!$this->session->userdata('logged_in')) {
			redirect('Welcome');
		} elseif ($prod_id && $this->input->post('edit_product') == NULL) {
			$data['title'] = ucwords('Edit Product Details');
			$data['username'] = $this->session->userdata('logged_in');
			$data['prod'] = $cust_data;
			$data['purList'] = $this->Product_model->get_all_purchaser();

			$this->load->view('layout/header', $data);
			$this->load->view('layout/menubar');
			$this->load->view('product_edit');
			$this->load->view('layout/footer');
		} elseif ($this->input->post('edit_product') != NULL) {
			// POST data
			$postData = $this->input->post();
			// $this->form_validation->set_rules('material_name', 'Material Name', 'trim|required');
			// $this->form_validation->set_rules('p_design_number', 'Design Number', 'trim|required|callback_regexValidate|edit_unique[products.design_number.' . $prod_id . ']');
			// $this->form_validation->set_rules('p_design_number', 'Design Number', 'trim|required');
			$this->form_validation->set_rules('owner_name', 'Owner Name', 'trim|required');
			// $this->form_validation->set_rules('p_price', 'Product Amount', 'trim|required|numeric');
			// $this->form_validation->set_rules('stock_q', 'Product in Stock', 'trim|required');
			// $this->form_validation->set_rules('pcs', 'Pcs', 'trim');
			// $this->form_validation->set_rules('meter', 'Meter', 'trim');
			// $this->form_validation->set_rules('product_exp', 'Expiry Da
			// $this->form_validation->set_rules('product_exp', 'Expiry Date', 'trim|required');
			// $this->form_validation->set_rules('price_total', 'Product Quantity', 'trim|required|numeric');

			if ($this->form_validation->run() == false) {
				$data['title'] = ucwords('Edit Product Details');
				$data['username'] = $this->session->userdata('logged_in');
				$data['purList'] = $this->Product_model->get_all_purchaser();
				$data['prod'] = $cust_data;

				$this->load->view('layout/header', $data);
				$this->load->view('layout/menubar');
				$this->load->view('product_edit');
				$this->load->view('layout/footer');
			} else {

				$material_name = implode(",", $this->input->post('material_name[]'));
				$material_name = trim($material_name, ",");

				$qnty = implode(",", $this->input->post('stock_q[]'));
				$qnty = trim($qnty, ",");

				$rate = implode(",", $this->input->post('p_price[]'));
				$rate = trim($rate,",");

				$amount = implode(",", $this->input->post('price_total[]'));
				$amount = trim($amount,",");

				// print_r($amount);

				$data = [
					'owner_name' => strtoupper($postData['owner_name']),
					'product_name' => $material_name,
					'total_amount' => $amount,
					'stock' => $qnty,
					'price' => $rate,
				];
				// print_r($data);
				// die();
				// $data = array(
				// 	'owner_name' => strtoupper($postData['owner_name']),
				// 	'product_name' => strtoupper($postData['material_name']),
				// 	'price' => $postData['p_price'],
				// 	'stock' => strtoupper($postData['stock_q']),
				// 	'total_amount' => $postData['price_total'],
				// );
				$prod_id = $postData["prod_id"];

				$update = $this->Product_model->update_product($data, $prod_id);
				// $product_id = $this->db->insert_id();
				// $data2 = array(
				// 	'product_id' => $prod_id,
				// 	'stock_qty' => $postData['stock_q'],
				// 	'purchase_rate' => $postData['p_price'],
				// 	// 'p_design_number' => $postData['p_design_number'],
				// );
				// $Store = $this->Stock_model->update_record($data2,$prod_id,);

				if ($update != -1) {
					$this->session->set_flashdata('success', 'Material details updated successfully.');
					redirect('Product');
				} else {
					$this->session->set_flashdata('failed', 'Some problem occurred, please try again.');
					$data['title'] = ucwords('Edit Material Details');
					$data['username'] = $this->session->userdata('logged_in');
					$data['purList'] = $this->Product_model->get_all_purchaser();
					$data['cust'] = $cust_data;
					$this->load->view('layout/header', $data);
					$this->load->view('layout/menubar');
					$this->load->view('product_edit');
					$this->load->view('layout/footer');
				}
			}
		}
	}

	// Logout from admin page
	public function logout()
	{
		$this->session->unset_userdata('logged_in');
		header("location:" . site_url('?status=loggedout'));
	}

	public function deleteProduct()
	{
		if ($this->input->post('row_id')) {
			$id = $this->input->post('row_id');
			$upd = $this->Product_model->delete_by_id($id);
			$stk = $this->Stock_model->delete_by_id($id);
			if ($upd > 0) {
				$resp['status'] = 'passed';
				$resp['result'] = 'Product deleted successfully.';
			} else {
				$resp['status'] = 'failed';
				$resp['result'] = 'Some problem occurred, please try again';
			}
			echo json_encode($resp);
		}
	}

	public function getProductDetail()
	{
		$pp_id = $this->input->post('p_id');
		$detail = $this->Product_model->getProductDetailbyId($pp_id);

		//print_r($detail);

		echo json_encode($detail);
	}


}
