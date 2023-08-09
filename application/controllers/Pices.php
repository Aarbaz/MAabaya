<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pices extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper('form');
		$this->load->model('Pices_model');
		$this->load->model('Stock_model');
		$this->load->model('Design_model');
		//$this->load->model('Product_model');
		$this->load->model('Material_model');
		$this->load->model('Making_model');
		$this->load->model('Customer_model');
		$this->load->model('Purchaser_model');
		$this->load->library('tcpdf');
		$this->load->model('Challan_model');
		$this->load->library('upload');
		//$this->load->helper('pdf_helper');
		$this->load->model('Balance_model');
		$this->load->helper('url');
		$this->load->helper('date');

	}

	public function index()
	{
		if ($this->session->userdata('logged_in')) {
			$data['title'] = ucfirst('PCs Recive');
			$data['username'] = $this->session->userdata('logged_in');
			$data['products'] = $this->Pices_model->get_products_in_pcs();
			$data["matList"] = $this->Purchaser_model->get_all_material();
			$data['data_list'] = $this->Pices_model->get_products_in_pcs_list();
			$data['invoice_list'] = $this->Challan_model->get_invoice_list();
			$this->load->view('layout/header', $data);
			$this->load->view('layout/menubar');
			$this->load->view('picesList.php', $data);
			$this->load->view('layout/footer');
		} else {
			redirect('Welcome');
		}
	}

	public function add_new()
	{
		if ($this->session->userdata('logged_in')) {

			$data['title'] = 'Add Purchaser Details';
			$data['username'] = $this->session->userdata('logged_in');
			$data["custList"] = $this->Customer_model->get_mowner();
			$data['materialList'] = $this->Purchaser_model->get_all_material();
			$data['designs'] = $this->Design_model->get_all_design();

			$data['last_invoice'] = $this->Pices_model->get_last_invoice_pices();
			$this->load->view('layout/header', $data);
			$this->load->view('layout/menubar');
			$this->load->view('pices_add', $data);
			$this->load->view('layout/footer');
		} else {
			redirect('Welcome');
		}
	}
	public function create()
	{
		$data['last_invoice'] = $this->Pices_model->get_last_invoice_pices();
		$this->form_validation->set_rules('customerName', 'customer Name', 'required');
		// $this->form_validation->set_rules('hsn_0[]', 'Select Design', 'trim|required');
		$this->form_validation->set_rules('qnty[]', 'Quantity', 'required');
		$this->form_validation->set_rules('rate[]', 'Rate', 'required');
		$steps = $this->input->post('steps');

		for ($i = 0; $i <= $steps; $i++) {
			$field_design = "hsn_" . $i . "[]";
			$field_total_piece = "total_piece_" . $i . "[]";
			$field_karigari = "karigari_" . $i . "[]";
			$field_total_karigari = "total_karigari_" . $i . "[]";
			$field_items = "items_" . $i . "[]";
			$field_total_material = "total_material_" . $i . "[]";

			$this->form_validation->set_rules(
				$field_design,
				"Select Design ",
				"trim|required"
			);
			$this->form_validation->set_rules(
				$field_total_piece,
				"Enter Total Pice ",
				"trim|required"
			);
			$this->form_validation->set_rules(
				$field_karigari,
				"Enter karigari ",
				"trim|required"
			);
			$this->form_validation->set_rules(
				$field_total_karigari,
				"Total Karigari ",
				"trim|required"
			);

			$this->form_validation->set_rules(
				$field_items,
				"Material Name ",
				"trim|required"
			);
			$this->form_validation->set_rules(
				$field_total_material,
				"Average ",
				"trim|required"
			);
		}

		// $this->form_validation->set_rules($validation);
		if ($this->form_validation->run() == false) {
			$response['result'] = $this->form_validation->error_array();
			$response['status'] = 'failed';
			$this->add_new();
		} else {

			$customer_id = $this->input->post('customerName');
			$all_material_ids = implode(',', $this->input->post('all_material_ids'));
			$all_material_ids = trim($all_material_ids, ',');

			$qnty = implode(',', $this->input->post('qnty[]'));
			$qnty = trim($qnty, ',');

			$rate = implode(',', $this->input->post('rate[]'));
			$rate = trim($rate, ',');

			$total_material_used = implode(',', $this->input->post('total_material_used'));
			$total_material_used = trim($total_material_used, ',');

			$material_used_array = explode(",", $total_material_used);

			$material_ids_array = explode(",", $all_material_ids);

			$total_amount = $this->input->post('total_amount');
			$total_round = $this->input->post('total_round');
			$total_word = $this->input->post('total_word');
			$paid_amount = $this->input->post('paid_amount');
			$balance_amount = $this->input->post('balance_amount');
			// Loop through the arrays and update the quantity for each material ID


			$steps = $this->input->post('steps');
			// Loop through each design number
			for ($i = 0; $i <= $steps; $i++) {
				// Create a new array for this design
				$design = array(
					'design_number' => $this->input->post('hsn_' . $i . '[]'),
					'materials_ids' => $this->input->post('items_' . $i . '[]'),
					'total_material' => $this->input->post('total_material_' . $i . '[]'),
					'total_material_used' => $this->input->post('total_material_used[]'),
					'total_piece' => $this->input->post('total_piece_' . $i . '[]'),
					'customer_id' => $this->input->post('customerName'),
					'invoice_no' => $this->input->post('invoice_no'),
					'labour_charge' => $this->input->post('karigari_' . $i . '[]'),
					'total' => $total_amount,
					'round_off_total' => $total_round,
					'total_in_words' => $total_word,
					'paid' => $paid_amount,
					'balance' => $balance_amount,
				);
				// Add the new design to the result array
				$result[] = $design;
			}


			// Convert the result array to JSON
			$json = json_encode($result);
			$data = $json;
			$k = 0;

			$array = json_decode($data, true);

			foreach ($result as $row) {

				$insert_data = [
					'design_number' => $row['design_number'][0],
					'material_id' => implode(',', $row['materials_ids']),
					'material_used' => $total_material_used,
					'total_piece' => $row['total_piece'][0],
					'master_id' => $row['customer_id'][0],
					'invoice_no' => $row['invoice_no']
				];

				// $data2 = array();

				$data2[] = array(
					'p_design_number' => $insert_data['design_number'],
					'stock_qty' => $insert_data['total_piece']
				);
				$materialData[] = array(
					'materials_id' => $insert_data['material_id'],
					'quantity' => $insert_data['material_used']
				);
				$materialId2 = $materialData[0]['materials_id'];
				$materialId3 = explode(",", $materialId2);

				$m = 0;
				$k++;
			}
			$json_data = array(
				'data_json' => $json,
				'master_id' => $this->input->post('customerName'),
				'invoice_no' => $this->input->post('invoice_no'),
			);

			$insert = $this->db->insert('product_pices', $json_data);


			if ($insert == true) {

				/***************** Pices Stock Update  *********************/
				foreach ($data2 as $row) {
					$product_id = $row['p_design_number'];
					$quantity = $row['stock_qty'];

					$this->db->where('p_design_number', $product_id);
					$query = $this->db->get('stock');
					$row = $query->row();
					if ($query->num_rows()) {
						// If the product exists, update the quantity value in the database
						$data3 = array(
							'stock_qty' => $row->stock_qty + $quantity
						);
						//print_r($data3);
						$this->db->where('p_design_number', $product_id);
						$this->db->update('stock', $data3);
					} else {
						// If the product does not exist, insert a new row into the database
						$this->db->insert('stock', array('p_design_number' => $product_id, 'stock_qty' => $quantity));
					}
				}
				/***************** Pices Stock Update end ******************/
				/***************** Material Stock Update  *********************/

				for ($i = 0; $i < count($material_ids_array); $i++) {
					// Check if the material ID exists in the maker_stock table for the customer ID
					$this->db->where('materials_id', $material_ids_array[$i]);
					//$this->db->where('making_owner_id', $customer_id);
					$q_result = $this->db->get('maker_stock')->result();

					if (empty($q_result) || count($q_result) == 0) {
						// Insert a new row with the material ID and quantity 0
						$m_data = array(
							'materials_id' => $material_ids_array[$i],
							//'making_owner_id' => $customer_id,
							'quantity' => $material_used_array[$i]
						);
						$this->db->insert('maker_stock', $m_data);
					} else {
						// Get the previous quantity for the material ID
						$material_ids = $material_ids_array[$i];
						$this->db->where_in('materials_id', $material_ids);
						//$this->db->where('making_owner_id', $customer_id);
						$prev_quantity = $this->db->get('maker_stock')->row()->quantity;
						// Update the quantity for the material ID with the previous quantity + new quantity
						$data = array('quantity' => $prev_quantity - $material_used_array[$i]);

						$this->db->where('materials_id', $material_ids_array[$i]);
						//$this->db->where('making_owner_id', $customer_id);
						$this->db->update('maker_stock', $data);
					}

				}
				/********************Material Stock Update end**********************/

				/********************Customer Balance Update**********************/
				$this->db->where('customer_id', $this->input->post('customerName'));
				$query = $this->db->get('balance');
				$row = $query->row();
				if ($query->num_rows()) {
					$data_balance = array(
						'balance_bill' => $row->balance_bill + $balance_amount,
						'paid_bill' => $row->paid_bill + $paid_amount,
						'total_bill' => $row->total_bill + $total_amount,
						"bill_type" => 'debited',
					);
					$bal_update = $this->Balance_model->update_balance($data_balance, $customer_id);

				} else {
					$data_balance = array(
						'customer_id' => $this->input->post('customerName'),
						'bill_no' => $this->input->post('invoice_no'),
						'total_bill' => $total_amount,
						'paid_bill' => $paid_amount,
						'balance_bill' => $balance_amount,
						"bill_type" => 'debited',
						'updated_on' => date('Y-m-d H:i:s')
					);
					$bal_insert = $this->Balance_model->insert_balance($data_balance);
				}
				/********************Customer Balance Update end**********************/

				/********************Customer Ledger Balance (History) ***************/
				$data_ledger = array(
					'customer' => $this->input->post('customerName'),
					'invoice' => $this->input->post('invoice_no'),
					'paid_amount' => $paid_amount,
					'bill_amount' => $total_amount,
					'last_amount' => $balance_amount,
					'entry_from' => 3,
					'dated' => date('Y-m-d H:i:s')
				);
				$insert = $this->Balance_model->add_customer_ledger($data_ledger);
				/********************Customer Ledger Balance (History) end**************/

				/********************Add In History Table     ****************/
				$json_data_array = array(
					'entry_from' => 3,
					//Pices
					'json_data' => $json,
				);

				$insert_json_data = $this->Pices_model->create_history($json_data_array);
				/********************AAdd In History Table end**************/

				$this->db->select('*');
				$this->db->from('customers');
				$this->db->where('id', $customer_id);
				$query = $this->db->get();
				$maker_name = $query->row();
				//$maker_name = json_decode($maker_name);

				$this->db->select('*');
				$this->db->from('material');
				$this->db->where_in('id', $materialId2);

				$query = $this->db->get();
				$results = $query->result();
				$material_names = '';
				foreach ($results as $result) {
					$material_names .= $result->material_name . ', ';
				}

				// Remove the trailing comma and space
				$material_names = rtrim($material_names, ', ');
				$data_pdf = [
					'customer' => $maker_name->name,
					'product_name' => $material_names,
					// 'hsn' => $hsn,
					'qnty' => $qnty,
					'invoice_no' => $this->input->post('invoice_no'),
					'customer_address' => $maker_name->address,
					'json_data' => $json_data,
				];
				$this->load->library('tcpdf/tcpdf.php');

				$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				$pdf->setPrintHeader(false);
				$pdf->setPrintFooter(false);
				$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT, true);
				//$pdf->SetFont('helvetica', '', 10);
				$pdf->SetFont('times', '', 10);
				$pdf_data = $this->load->view('invoice_pieces', $data_pdf, true);
				$pdf->addPage();
				$pdf->writeHTML($pdf_data, true, false, true, false, '');

				$filename = $this->input->post('invoice_no') . '.pdf';
				$dir = APPPATH . '/pices_invoice/' . $data_pdf['customer'] . '/';
				if (!is_dir($dir)) {
					mkdir($dir, 0777, true);
				}
				$save_path = $dir . $filename;
				ob_end_clean();
				$pdf->Output($save_path, 'F');
				$this->session->set_flashdata('success', 'Data Added successfully....');
				redirect('Pices/');
			} else {
				$this->session->set_flashdata('fail', "Sorry! there was some error.");
				redirect(base_url('/index.php/Pices/add_new'));
			}
		}
	}

	public function editPices($pices_id)
	{

		if (!$this->session->userdata('logged_in')) {
			redirect('Welcome');
		} elseif ($this->input->post('edit_purchaser') == NULL) {
			$pice_data = $this->Pices_model->get_pices_byID($pices_id);
			$data['title'] = 'Edit Purchaser Details';
			$data['username'] = $this->session->userdata('logged_in');
			$data["custList"] = $this->Customer_model->get_mowner();
			$data['materialList'] = $this->Purchaser_model->get_all_material();
			$data['designs'] = $this->Design_model->get_all_design();
			$data["pices"] = $pice_data;

			$this->load->view('layout/header', $data);
			$this->load->view('layout/menubar');
			$this->load->view('pices_edit');
			$this->load->view('layout/footer');

		} elseif ($this->input->post('edit_purchaser') != NULL) {
			$postData = $this->input->post();

			$this->form_validation->set_rules('customerName', 'customer Name', 'required');
			//$this->form_validation->set_rules('amount[]', 'Total Material', 'required');

			if ($this->form_validation->run() == false) {
				$data['title'] = 'Edit Purchaser Details';
				$data['username'] = $this->session->userdata('logged_in');
				$data['cust'] = $cust_data;

				$this->load->view('layout/header', $data);
				$this->load->view('layout/menubar');
				$this->load->view('Purchaser_edit');
				$this->load->view('layout/footer');
			} else {
				$material = implode(',', $this->input->post('items[]'));
				$material = trim($material, ',');

				$selected_ids = implode(',', $this->input->post('selected_ids'));
				$selected_ids = trim($selected_ids, ',');

				$material_ids = implode(',', $this->input->post('material_ids'));
				$material_ids = trim($material_ids, ',');

				$hsn = implode(',', $this->input->post('hsn[]'));
				$hsn = trim($hsn, ',');
				$qnty = implode(',', $this->input->post('qnty[]'));
				$qnty = trim($qnty, ',');

				$rate = implode(',', $this->input->post('rate[]'));
				$rate = trim($rate, ',');

				$amount = implode(',', $this->input->post('amount[]'));
				$amount = trim($amount, ',');

				$customer_id = $this->input->post('customerName');
				$invoice_no = $this->input->post('invoice_no');
				$transport_charges = $this->input->post('trans_charge');
				$other_charge = $this->input->post('other_charge');
				$total_taxable_amount = $this->input->post('total_tax_value');
				$igst_5_cent = $this->input->post('igst_charge');
				$cgst_charge = $this->input->post('cgst_charge');
				$sgst_charge = $this->input->post('sgst_charge');

				$cgst_per = $this->input->post('cgst_per');
				$sgst_per = $this->input->post('sgst_per');
				$igst_per = $this->input->post('igst_per');

				$total_amount = $this->input->post('total_amount');
				$total_round = $this->input->post('total_round');
				$total_word = $this->input->post('total_word');
				$sup_date = $this->input->post('sup_date');
				//$sup_place = $this->input->post('sup_place');
				$sup_other = $this->input->post('sup_other');

				$data = array(
					'master_id' => $customer_id,
					'mat_name' => $material,
					'material_id' => $material_ids,
					'design_number' => $hsn,
					'pices' => $qnty,
					'average' => $rate,
					'material_used' => $amount,

				);

				$update = $this->Pices_model->update_records($data, $pices_id);
				// Get the product and quantity values from your input
				$selected_ids_values = explode(",", $selected_ids);
				$product_values = $selected_ids_values; // Dynamic product values
				$qnty_values = explode(",", $qnty);
				$quantity_values = ($qnty_values); // Dynamic quantity values

				// Prepare the data to be inserted
				$data2 = array();
				for ($i = 0; $i < count($product_values); $i++) {
					$data2[] = array(
						'p_design_number' => $product_values[$i],
						'stock_qty' => $quantity_values[$i]
					);
				}
				$this->db->trans_start(); // Start a transaction to ensure data consistency
				foreach ($data2 as $row) {
					$product_id = $row['p_design_number'];
					$quantity = $row['stock_qty'];

					$this->db->where('p_design_number', $product_id);
					$query = $this->db->get('stock');
					$row = $query->row();
					if ($query->num_rows()) {
						// If the product exists, update the quantity value in the database
						$data2 = array(
							'stock_qty' => $row->stock_qty + $quantity
						);
						$this->db->where('p_design_number', $product_id);
						$this->db->update('stock', $data2);
					} else {
						// If the product does not exist, insert a new row into the database
						$this->db->update('stock', array('p_design_number' => $product_id, 'stock_qty' => $quantity));
					}
				}
				$this->db->trans_complete(); // End the transaction

				if ($this->db->trans_status() === false) {
					// Handle transaction failure
					$this->session->set_flashdata('success', 'Stock Updated successfully....');
				}
				if ($update != -1) {
					$this->session->set_flashdata('success', 'Pices details updated successfully.');
					redirect('Pices');
				} else {
					$this->session->set_flashdata('failed', 'Some problem occurred, please try again.');
					$data['title'] = ucwords('Edit Pices Details');
					$data['username'] = $this->session->userdata('logged_in');
					$data['cust'] = $cust_data;
					$this->load->view('layout/header', $data);
					$this->load->view('layout/menubar');
					$this->load->view('Pices');
					$this->load->view('layout/footer');
				}
			}
		}
	}

	public function deletePices()
	{
		if ($this->input->post('row_id')) {
			$id = $this->input->post('row_id');
			$upd = $this->Pices_model->delete_by_id($id);
			if ($upd > 0) {
				$resp['status'] = 'passed';
				$resp['result'] = 'Pices deleted successfully.';
			} else {
				$resp['status'] = 'failed';
				$resp['result'] = 'Some problem occurred, please try again';
			}
			echo json_encode($resp);
		}
	}

	//Download pdf invoice
	public function downloadPdf($cust_name, $invoice_id)
	{

		if (!$this->session->userdata('logged_in')) {
			redirect('Welcome');
		} elseif ($cust_name && $invoice_id) {
			$pdf_file = APPPATH . 'pices_invoice/' . rawurldecode($cust_name) . '/' . $invoice_id . '.pdf';
			$file = $invoice_id . '.pdf';

			if (file_exists($pdf_file)) {
				header("Content-Type: application/pdf");
				header("Content-Disposition: attachment;filename=\"$file\"");
				readfile($pdf_file);
			} else {
				$this->session->set_flashdata('no_pdf', 'Sorry! file not found...');
				redirect('Pices');
			}
		} else {
			$data['title'] = ucwords('Page not found');
			$data['username'] = $this->session->userdata('logged_in');
			$this->load->view('layout/header', $data);
			$this->load->view('layout/menubar');
			$this->load->view('errors/html/error_404');
			$this->load->view('layout/footer');
		}
	}
	public function downloadBalance()
	{

		$cust_name = $this->input->post('customerName');
		$frm_mth = $this->input->post('frm_mth');
		$frm_yr = $this->input->post('frm_yr');
		$to_mth = $this->input->post('to_mth');
		$to_yr = $this->input->post('to_yr');
		$invoice_id = $frm_mth . '_' . $to_mth;
		if ($this->form_validation->run() == FALSE) {
			$response['result'] = 'Please select customer, month and year.';
			$response['status'] = 'failed';
			//echo json_encode($response);
		}

		$this->form_validation->set_rules('customerName', 'Customer Name', 'trim|required');
		$this->form_validation->set_rules('frm_mth', 'From Month', 'trim|required');
		$this->form_validation->set_rules('frm_yr', 'From Year', 'trim|required');
		if (!$this->session->userdata('logged_in')) {
			redirect('Welcome');
		} elseif ($cust_name && $invoice_id) {
			$db_data = $this->Balance_model->customer_ledger_byDate($cust_name, $frm_mth, $frm_yr, $to_mth, $to_yr)->result_array();

			// get user name
			$this->db->where('id', $cust_name);
			$query = $this->db->get('customers');
			$row = $query->row();
			$username = $row->name;
			$cust_id = $cust_name;
			$cust_name = rawurlencode($username);

			$pdf_file = APPPATH . 'balance_sheet/' . rawurldecode($cust_name) . '/' . $invoice_id . '.pdf';
			$file = $invoice_id . '.pdf';

			if (file_exists($pdf_file)) {
				header("Content-Type: application/pdf");
				header("Content-Disposition: attachment;filename=\"$file\"");
				readfile($pdf_file);
			} else {
				$db_data = $this->Balance_model->customer_ledger_byDate($cust_id, $frm_mth, $frm_yr, $to_mth, $to_yr)->result_array();

				$data_pdf = $db_data; // Replace $dynamic_array with your actual dynamic array
				$filename = $frm_mth . '_' . $to_mth . '.pdf';
				$pdf_file = APPPATH . 'balance_sheet/' . rawurldecode($username) . '/' . $filename;
				$file = $filename;

				$start_date = $frm_mth . '/' . $frm_yr;
				$end_date = $to_mth . '/' . $to_yr;

				list($start_month, $start_year) = explode('/', $start_date);
				$start_timestamp = strtotime($start_year . '-' . $start_month . '-01');

				list($end_month, $end_year) = explode('/', $end_date);
				$end_timestamp = strtotime($end_year . '-' . $end_month . '-01');

				$formatted_start_date = date('M Y', $start_timestamp);
				$formatted_end_date = date('M Y', $end_timestamp);

				$date_range = $formatted_start_date . ' - ' . $formatted_end_date;

				$this->load->library('tcpdf/tcpdf.php');

				$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				$pdf->setPrintHeader(false);
				$pdf->setPrintFooter(false);
				$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT, true);
				//$pdf->SetFont('helvetica', '', 10);
				$pdf->SetFont('times', '', 10);

				$pdf_data = $this->load->view('balance_pdf', array('data_pdf' => $data_pdf, 'date_range' => $date_range, 'cust_name' => $cust_name), true);
				$pdf->addPage();

				$pdf->writeHTML($pdf_data, true, false, true, false, '');

				$inv_id = $frm_mth . '_' . $to_mth;
				$dir = APPPATH . '/balance_sheet/' . $username . '/';

				if (!is_dir($dir)) {
					mkdir($dir, 0777, true);
				}
				$save_path = $dir . $filename;
				ob_end_clean();
				$pdf->Output($save_path, 'F');
				header("Content-Type: application/pdf");
				header("Content-Disposition: attachment;filename=\"$filename\"");
				readfile($save_path);

				$response['result'] = 'PDF generated successfully.';
				$response['status'] = 'passed';

				$this->session->set_flashdata('success', 'PDF generated successfully....');
				sleep(4);
				// $this->downloadPdf($cust_name, $invoice_id);
			}
		} else {
			$data['title'] = ucwords('Page not found');
			$data['username'] = $this->session->userdata('logged_in');
			$this->load->view('layout/header', $data);
			$this->load->view('layout/menubar');
			$this->load->view('errors/html/error_404');
			$this->load->view('layout/footer');
		}
	}

	public function returnPices()
	{
		$data['last_invoice'] = $this->Pices_model->get_last_invoice_pices();
		$this->form_validation->set_rules('customerName', 'customer Name', 'required');
		$this->form_validation->set_rules('design_no[]', 'Quantity', 'required');
		$this->form_validation->set_rules('return_qnty[]', 'Rate', 'required');
		if ($this->form_validation->run() == false) {
			$response['result'] = $this->form_validation->error_array();
			$response['status'] = 'failed';
			$this->add_new();
		} else {
			$customerName = $this->input->post('customerName');
			$invoice_no = $this->input->post('invoice_no');
			$design_nos = implode(',', $this->input->post('design_no[]'));
			$design_nos = trim($design_nos, ',');

			$return_qntys = implode(',', $this->input->post('return_qnty[]'));
			$return_qntys = trim($return_qntys, ',');

			$data = $this->input->post();
			$input_array = array(
				'design_number' => $this->input->post('design_no[]'),
				'customer_id' => $customerName,
				'invoice_no' => $invoice_no,
				'total_piece' => $this->input->post('return_qnty[]'),
			);

			$output_array = array();

			for ($i = 0; $i < count($input_array['design_number']); $i++) {
				$item = array(
					"design_number" => [$input_array['design_number'][$i]],
					"customer_id" => $input_array['customer_id'],
					"invoice_no" => $input_array['invoice_no'],
					"total_piece" => [$input_array['total_piece'][$i]]
				);
			
				$output_array[] = $item;
			}
			$design_json = json_encode($output_array);
			$json_data = array(
				'data_json' => $design_json,
				'master_id' => $customerName,
				'invoice_no' => $invoice_no,
			);

			$insert = $this->db->insert('product_pices', $json_data);
	
			foreach ($data['design_no'] as $index => $design_no) {
				$return_qnty = $data['return_qnty'][$index];

				// Check if design_no exists in the table
				$existing_record = $this->db->get_where('stock', array('p_design_number' => $design_no))->row();
				if ($existing_record) {
					$qnty_data = array(
						'stock_qty' => $existing_record->stock_qty + $return_qnty
					);
					// Design number exists, update return quantity
					$this->db->where('p_design_number', $design_no);
					$result = $this->db->update('stock', $qnty_data);

				} else {
					// Design number doesn't exist, insert new record
					$result = $this->db->insert('stock', array('p_design_number' => $design_no, 'stock_qty' => $return_qnty));
				}

			}
			if ($result) {
				$this->db->where('id', $customerName);
				$query = $this->db->get('customers');
				$row = $query->row();
				$username = $row->name;
				$product_ids = explode(',', $design_nos); // Replace this with your array of product IDs
				$product_names = array();
				foreach ($product_ids as $product_id) {
					$this->db->select('design_num');
					$this->db->from('designs');
					$this->db->where('id', $product_id);

					$query = $this->db->get();
					$product = $query->row(); // Get a single row as an object

					if ($product) {
						$product_names[] = $product->design_num;
					}
				}

				$products = implode(',', $product_names);

				$data_pdf = [
					'customer' => $username,
					'design_no' => $products,
					'qnty' => $return_qntys,
					'invoice_no' => $invoice_no,
				];
				$this->load->library('tcpdf/tcpdf.php');

				$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				$pdf->setPrintHeader(false);
				$pdf->setPrintFooter(false);
				$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT, true);
				//$pdf->SetFont('helvetica', '', 10);
				$pdf->SetFont('times', '', 10);
				$pdf_data = $this->load->view('invoice_pieces_return', $data_pdf, true);
				$pdf->addPage();
				$pdf->writeHTML($pdf_data, true, false, true, false, '');

				$filename = $invoice_no . '.pdf';
				$dir = APPPATH . '/pices_invoice/' . $data_pdf['customer'] . '/';
				if (!is_dir($dir)) {
					mkdir($dir, 0777, true);
				}
				$save_path = $dir . $filename;
				ob_end_clean();
				$pdf->Output($save_path, 'F');
				$this->session->set_flashdata('success', 'Data Added successfully....');
				redirect('Pices/');
			} else {
				$this->session->set_flashdata('error', 'Something Went Wrong!');
				redirect('Pices/');
			}

		}
	}
}
?>