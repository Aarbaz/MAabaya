<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Invoice extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->library('tcpdf');
		$this->load->model('Challan_model');
		$this->load->model('Pices_model');
		$this->load->model('Stock_model');
		$this->load->model('History_model');
		$this->load->model('Design_model');
		$this->load->model('Product_model');
		$this->load->model('Material_model');
		$this->load->model('Balance_model');
		$this->load->library('upload');
		//$this->load->helper('pdf_helper');
		$this->load->helper('url');
		//$this->load->library('pdf');
	}

	public function index()
	{
		if ($this->session->userdata('logged_in')) {
			$data['title'] = 'Invoice Listing';
			$data['username'] = $this->session->userdata('logged_in');
			$data['invoice_list'] = $this->Challan_model->get_invoice_list();
			$this->load->view('layout/header', $data);
			$this->load->view('layout/menubar');
			$this->load->view('invoiceList', $data);
			$this->load->view('layout/footer');
		} else {
			redirect('Welcome');
		}
	}

	public function create()
	{
		if ($this->session->userdata('logged_in')) {
			$data['title'] = 'Create new invoice';
			$data['username'] = $this->session->userdata('logged_in');
			$data['custList'] = $this->Challan_model->get_all_customer();
			$data['productList'] = $this->Challan_model->get_all_products();
			$data['last_invoice'] = $this->Challan_model->get_last_invoice_insider();
			$data['designs'] = $this->Design_model->get_all_design();
			$this->load->view('layout/header', $data);
			$this->load->view('layout/menubar');
			$this->load->view('invoice_inside', $data);
			$this->load->view('layout/footer');
		} else {
			redirect('Welcome');
		}
	}

	public function test()
	{
		if ($this->session->userdata('logged_in')) {
			$data['title'] = 'Create new invoice';
			$data['username'] = $this->session->userdata('logged_in');
			$data['custList'] = $this->Challan_model->get_all_customer();
			$data['productList'] = $this->Challan_model->get_all_products();
			$data['last_invoice'] = $this->Challan_model->get_last_invoice_insider();
			$this->load->view('layout/header', $data);
			$this->load->view('layout/menubar');
			$this->load->view('invoice_inside - Copy', $data);
			$this->load->view('layout/footer');
		} else {
			redirect('Welcome');
		}
	}
	//add insider invoice
	public function add_insider_invoice()
	{

		$this->form_validation->set_rules('customerName', 'customer Name', 'required');
		/* $this->form_validation->set_rules('hsn', 'Select Design', 'required');
			  $this->form_validation->set_rules('qnty', 'Quantity', 'required');
			  $this->form_validation->set_rules('rate', 'Rate', 'required'); */
		/* $validation = array(
				  array(
					  'field' => 'items[]',
					  'label' => 'Product',
					  'rules' => 'required',
					  "errors" => array('required' => " Please select %s. ")
				  ),
			  ); */

		// $this->form_validation->set_rules($validation);
		// $this->form_validation->set_rules('hsn[]', 'Design', 'required');
		$this->form_validation->set_rules('qnty[]', 'Quantity', 'required');
		$this->form_validation->set_rules('rate[]', 'Rate', 'required');
		$this->form_validation->set_rules('amount[]', 'Amount', 'required');
		$this->form_validation->set_rules('total_word', 'Total Amount in Words', 'required');
		// $this->form_validation->set_rules('total_word[]', 'Total Amount in words', 'required');

		if ($this->form_validation->run() == false) {
			$this->create();
		} else {
			$material = implode(',', $this->input->post('items[]'));
			$material = trim($material, ',');
			$selected_ids = implode(',', $this->input->post('selected_ids'));
			$selected_ids = trim($selected_ids, ',');
			// $stk = implode(',', $this->input->post('stk[]'));
			// $stk = trim($stk, ',');
			$hsn = implode(',', $this->input->post('hsn[]'));
			$hsn = trim($hsn, ',');
			$qnty = implode(',', $this->input->post('qnty[]'));
			$qnty = trim($qnty, ',');

			$rate = implode(',', $this->input->post('rate[]'));
			$rate = trim($rate, ',');

			$amount = implode(',', $this->input->post('amount[]'));
			$amount = trim($amount, ',');

			$bakers_id = $this->input->post('customerName');
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
			$paid_amount = $this->input->post('paid_amount');
			$balance_amount = $this->input->post('balance_amount');
			$sup_date = $this->input->post('sup_date');
			//$sup_place = $this->input->post('sup_place');
			$sup_other = $this->input->post('sup_other');

			$qnty_array = $this->input->post('qnty[]');
			$qnty_sum = 0;
			foreach ($qnty_array as $value) {
				$qnty_sum += $value;
			}

			if ($this->input->post('bill_date')) {
				$date = $this->input->post('bill_date');
			} else {
				$date = date("Y-m-d");
			}

			$data = array(
				'customer_id' => $bakers_id,
				'invoice_no' => $invoice_no,
				'customer_address' => $this->input->post('cust_adds_txt'),
				'product_name' => $hsn,
				//'stk'		=> $stk,
				'hsn' => $selected_ids,
				'qnty' => $qnty,
				'rate' => $rate,
				'amount' => $amount,
				'transport_charges' => $transport_charges,
				'other_charge' => $other_charge,
				'total_taxable_amount' => $total_taxable_amount,
				'igst_5_cent' => $igst_5_cent,
				'cgst_2_5_cent' => $cgst_charge,
				'sgst_2_5_cent' => $sgst_charge,
				'total' => $total_amount,
				'round_off_total' => $total_round,
				'total_in_words' => $total_word,
				'date_of_supply' => $sup_date,
				//'place_of_supply'  => $sup_place,
				'other_notes' => $sup_other,
				'paid' => $paid_amount,
				'balance' => $balance_amount,
				'invoice_date' => $date
			);

			$data_balance = array(
				'customer_id' => $bakers_id,
				'bill_no' => $invoice_no,
				'total_bill' => $total_amount,
				'paid_bill' => $paid_amount,
				'balance_bill' => $balance_amount,
				'updated_on' => date('Y-m-d H:i:s')
			);

			$data_ledger = array(
				'customer' => $bakers_id,

				'invoice' => $invoice_no,
				'quantity' => $qnty,
				'rate' => $rate,
				'bill_amount' => $total_amount,
				'paid_amount' => $paid_amount,
				'last_amount' => $balance_amount,
				'dated' => date('Y-m-d H:i:s')
			);
			$this->db->select('*');
			$this->db->from('customers');
			$this->db->where('id', $bakers_id);
			$query = $this->db->get();
			$customers_name = $query->row();
			$data_pdf = array(
				'customer' => $customers_name->name,
				'customer_address' => $this->input->post('cust_adds_txt'),
				'gst' => $this->input->post('cust_gst'),
				'invoice_no' => $invoice_no,
				'product_name' => $material,
				// /'stk'		=> $stk,
				'hsn' => $hsn,
				'qnty' => $qnty,
				'rate' => $rate,
				'amount' => $amount,
				'transport_charges' => $transport_charges,
				'other_charge' => $other_charge,
				'total_taxable_amount' => $total_taxable_amount,
				'igst_5_cent' => $igst_5_cent,
				'cgst_2_5_cent' => $cgst_charge,
				'sgst_2_5_cent' => $sgst_charge,
				'cgst_per' => $cgst_per,
				'sgst_per' => $sgst_per,
				'igst_per' => $igst_per,
				'total' => $total_amount,
				'round_off_total' => $total_round,
				'total_in_words' => $total_word,
				'date_of_supply' => $sup_date,
				//'place_of_supply'  => $sup_place,
				'other_notes' => $sup_other,
				'paid' => $paid_amount,
				'balance' => $balance_amount,
				'qnty_sum' => $qnty_sum,
				'date' => $date
			);

			$selected_ids_values = explode(",", $selected_ids);
			$product_values = $selected_ids_values; // Dynamic product values
			$qnty_values = explode(",", $qnty);
			$quantity_values = ($qnty_values);
			$insert = $this->Challan_model->create_invoice_insider($data);
			/* $insert = $this->Challan_model->create_balance($data_balance);*/
			$insert = $this->Balance_model->add_customer_ledger($data_ledger);

			if ($balance_amount) {

				$this->db->where('customer_id', $bakers_id);
				$query = $this->db->get('balance');
				$row = $query->row();
				if ($query->num_rows()) {
					$data3 = array(
						'balance_bill' => $row->balance_bill + $balance_amount,
						'paid_bill' => $row->paid_bill + $paid_amount,
						'total_bill' => $row->total_bill + $total_amount,
						"bill_type" => 'credited',
					);
					// $this->db->where('customer_id',$customer_id);
					// $this->db->update('balance', $data3);
					$bal_update = $this->Balance_model->update_balance($data3, $bakers_id);

				} else {
					$bal_data = [
						"customer_id" => $bakers_id,
						"bill_type" => 'debited',
						"bill_no" => strtoupper($invoice_no),
						"total_bill" => $total_amount,
						"paid_bill" => $paid_amount,
						"balance_bill" => $balance_amount,
						"bill_type" => 'credited',
					];
					$bal_insert = $this->Balance_model->insert_balance($bal_data);
				}
			}
			if ($insert == true) {
				$data2 = array();
				for ($i = 0; $i < count($product_values); $i++) {
					$data2[] = array(
						'p_design_number' => $product_values[$i],
						'stock_qty' => $quantity_values[$i]
					);
				}

				// Update Stock
				// $stock = $this->Stock_model->add_record($data2);
				$this->db->trans_start(); // Start a transaction to ensure data consistency
				$json_data = json_encode($data);
				$entry_from = 4;
				$user_id = $bakers_id;
				$invoice_id = strtoupper($invoice_no);
				foreach ($data2 as $row) {

					$product_id = $row['p_design_number'];
					$quantity = $row['stock_qty'];
					$in_out_qty = $quantity;
					$this->db->where('p_design_number', $product_id);
					$query = $this->db->get('stock');
					$previous_qty = $query->row();

					// $finalValue=0;
					if ($query->num_rows() && $previous_qty->stock_qty) {
						// $pice_id = $row->id;
						// If the product exists, update the quantity value in the database
						$data2 = array(
							'stock_qty' => $previous_qty->stock_qty - $quantity
						);
						$this->db->where('p_design_number', $product_id);
						$this->db->update('stock', $data2);

						$product_id = $row['p_design_number'];
						$quantity = $row['stock_qty'];
						$in_out_qty = -1 * $quantity;
						$this->db->where('p_design_number', $product_id);
						$query = $this->db->get('stock');
						$previous_qty = $query->row();
						$current_qty = $previous_qty->stock_qty;

						$this->History_model->insertStockEntry($entry_from, $user_id, $invoice_id, $product_id, $in_out_qty, $current_qty, $json_data);
					} else {
						// If the product does not exist, insert a new row into the database
						$this->db->insert('stock', array('p_design_number' => $product_id, 'stock_qty' => -1 * $quantity));
						$current_qty = -1 * $in_out_qty;
						$this->History_model->insertStockEntry($entry_from, $user_id, $invoice_id, $product_id, $current_qty, $current_qty, $json_data);
					}
				}
				$this->db->trans_complete(); // End the transaction

				if ($this->db->trans_status() === false) {
					// Handle transaction failure
					$this->session->set_flashdata('success', 'Stock Updated successfully....');
				}
				$this->load->library('tcpdf/tcpdf.php');

				$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				$pdf->setPrintHeader(false);
				$pdf->setPrintFooter(false);
				$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT, true);
				//$pdf->SetFont('helvetica', '', 10);
				$pdf->SetFont('times', '', 10);
				$pdf_data = $this->load->view('invoice_pdf', $data_pdf, true);
				$pdf->addPage();
				$pdf->writeHTML($pdf_data, true, false, true, false, '');

				$filename = $this->input->post('invoice_no') . '.pdf';
				// print_r($data_pdf['customer']);
				// die();
				$dir = APPPATH . '/invoice/' . $customers_name->id . '/';

				if (!is_dir($dir)) {
					mkdir($dir, 0777, true);
				}
				$save_path = $dir . $filename;
				ob_end_clean();
				// $pdf->Output($save_path, 'I');
				$pdf->Output($save_path, 'F');
				//file_put_contents($save_path, $pdf);
				$this->session->set_flashdata('success', 'Invoice created successfully....');
				redirect('Invoice/');
			} else {
				$this->session->set_flashdata('fail', "Sorry! there was some error.");
				redirect(base_url('/index.php/Invoice/create'));
			}
		}
	}

	public function edit_sell_data($sell_id)
	{
		$this->form_validation->set_rules('customerName', 'customer Name', 'required');
		if ($this->form_validation->run() == false) {
			$response['result'] = $this->form_validation->error_array();
			$response['status'] = 'failed';
			// $this->create();
		} else {

			$material = implode(',', $this->input->post('hsn[]'));
			$material = trim($material, ',');
			$selected_ids = implode(',', $this->input->post('selected_ids'));
			$selected_ids = trim($selected_ids, ',');

			$hsns = implode(',', $this->input->post('hsn[]'));
			// $hsn = trim($hsn, ',');

			$qnty = implode(',', $this->input->post('qnty[]'));
			// $qnty = trim($qnty, ',');

			$rate = implode(',', $this->input->post('rate[]'));
			$rate = trim($rate, ',');

			$amount = implode(',', $this->input->post('amount[]'));
			$amount = trim($amount, ',');

			$bakers_id = $this->input->post('customerName');
			$invoice_no = $this->input->post('invoice_no');
			$total_word = $this->input->post('total_word');
			$total_round = $this->input->post('total_round');
			$total_amount = $this->input->post('total_amount');
			$paid_amount = $this->input->post('paid_amount');
			$balance_amount = $this->input->post('balance_amount');


			$get_ledger_invoice = $this->Balance_model->get_bal_user_bill($invoice_no);

			$ledger_bill2 = $get_ledger_invoice->bill_amount;
			$paidBill2 = $get_ledger_invoice->paid_amount;
			$ledger_last2 = $get_ledger_invoice->last_amount;


			$get_ledger_invoice = $this->Balance_model->get_billcust($bakers_id);
			$ledger_bill = $get_ledger_invoice[0]->total_bill;
			$paidBill = $get_ledger_invoice[0]->paid_bill;
			$ledger_last = $get_ledger_invoice[0]->balance_bill;
			$qnty_array = $this->input->post('qnty[]');
			$qnty_sum = 0;
			// Assume $material and $qnty are obtained from your form

			$materialArray = explode(',', $selected_ids);
			$qntyArray = explode(',', $qnty);
			$dataToUpdate = array_combine($materialArray, $qntyArray);

			//$this->Stock_model->updateStock($dataToUpdate);
			//die();
			foreach ($qnty_array as $value) {
				$qnty_sum += $value;
			}

			if ($this->input->post('bill_date')) {
				$date = $this->input->post('bill_date');
			} else {
				$date = date("Y-m-d");
			}

			$existingData = $this->History_model->get_sell_by_id($sell_id); // Replace 'get_data_by_id' with the actual method in your model to retrieve the existing data
			if ($existingData) {

				$existing_design = explode(",", $existingData["hsn"]);
				$existing_qnty = explode(",", $existingData["qnty"]);
				$existing_rate = explode(",", $existingData["rate"]);
				$existing_amount = explode(",", $existingData["amount"]);
				$hsn = $this->input->post('hsn[]'); // Your incoming new data as an array
				$design_ids = explode(',', $selected_ids);
				// Create associative arrays with design numbers as keys and quantities as values
				$assocExisting = array_combine($existing_design, $existing_qnty);
				$assocNew = array_combine($design_ids, $qnty_array);
				// Find the difference based on both design numbers and quantities
				$added = array_diff_key($assocNew, $assocExisting);
				$removed = array_diff_key($assocExisting, $assocNew);
				$value_changed = array_diff_assoc($assocNew, $assocExisting);
				$value_null = '0';
				if ($ledger_bill2) {
					$diff_total = (float) $total_amount - (float) $ledger_bill2;

				} else {
					$diff_total = (float) $total_amount - (float) $value_null;
				}
				// print_r($diff_total);

				if ($diff_total > 0) {
					// echo "The diff_totalerence is positive: " . $diff_total;	
					$total_bill_new = (float) $ledger_bill + (float) $diff_total;
				} elseif ($diff_total < 0) {
					$total_bill_new = (float) $ledger_bill - (float) abs($diff_total);
				} else {
					$total_bill_new = (float) $ledger_bill + (float) $diff_total;
				}


				if ($paidBill2) {
					$diff_paid = (float) $paid_amount - (float) $paidBill2;

				} else {
					$diff_paid = (float) $paid_amount - (float) $value_null;
				}
				// print_r($diff_paid);

				if ($diff_paid > 0) {
					// echo "The diff_paiderence is positive: " . $diff_paid;	
					$paid_bill_new = (float) $paidBill + (float) $diff_paid;
				} elseif ($diff_paid < 0) {
					$paid_bill_new = (float) $paidBill - (float) abs($diff_paid);
				} else {
					$paid_bill_new = (float) $paidBill + (float) $diff_paid;
				}

				if ($ledger_last2) {
					$diff_bal = (float) $balance_amount - (float) $ledger_last2;

				} else {
					$diff_bal = (float) $balance_amount - (float) $value_null;
				}

				// print_r($diff_bal);

				if ($diff_bal > 0) {
					// echo "The diff_balerence is positive: " . $diff_bal;	
					$bal_bill_new = (float) $ledger_last + (float) $diff_bal;

				} elseif ($diff_bal < 0) {
					// echo "The diff_balerence is negative: " . abs($diff_bal);
					$bal_bill_new = (float) $ledger_last - (float) abs($diff_bal);
				} else {
					$bal_bill_new = (float) $ledger_last + (float) $diff_bal;
				}
				$data_balances = array(
					'customer_id' => $bakers_id,
					'bill_no' => $invoice_no,
					'total_bill' => $total_bill_new,
					'paid_bill' => $paid_bill_new,
					'balance_bill' => $bal_bill_new,
					'updated_on' => date('Y-m-d H:i:s')
				);

				$bal_update = $this->Balance_model->update_balanceBybill($data_balances, $bakers_id, $invoice_no);

				/* if ($added) {
					foreach ($added as $key => $value) {

						// You can add your logic here based on the key or value if needed
						$this->db->select('*');
						$this->db->from('designs');
						$this->db->where('design_num', $key);
						$query = $this->db->get();
						$product = $query->row(); // Get a single row as an object

						$this->db->where('p_design_number', $product->id);
						$query = $this->db->get('stock');
						$row = $query->row();
						if ($query->num_rows()) {
							if ($row->stock_qty < 0) {
								$data3 = array(
									'stock_qty' => (float) $row->stock_qty - (float) $value,
								);
							}else {
								$data3 = array(
									'stock_qty' => (float) $row->stock_qty + (float) $value,
								);
							}
							
							$this->db->where('p_design_number', $product->id);
							$this->db->update('stock', $data3);
						}
					}
				}
				if ($removed) {
					foreach ($removed as $key_remove => $value_remove) {

						// You can add your logic here based on the key_remove or value_remove if needed
						$this->db->select('*');
						$this->db->from('designs');
						$this->db->where('design_num', $key_remove);
						$query = $this->db->get();
						$product = $query->row(); // Get a single row as an object
						$this->db->where('p_design_number', $product->id);
						$query = $this->db->get('stock');
						$row = $query->row();
						if ($query->num_rows()) {
							$data3_remove = array(
								'stock_qty' => (float) $row->stock_qty - (float) $value_remove,
							);
							$this->db->where('p_design_number', $product->id);
							$this->db->update('stock', $data3_remove);
						}
					}
				} */
				if ($value_changed) {
						foreach ($value_changed as $changed_value => $value_change) {

							// You can add your logic here based on the changed_value or value_change if needed
							$this->db->select('hsn,qnty');
							$this->db->from('insider_bill');
							$this->db->where('sr_no', $sell_id);
							$query = $this->db->get();
							$designs = $query->row(); // Get a single row as an object
							// Previous values
							// print_r($existing_qnty);die();
							// $previous_product_ids_str = $existing_design;
							// $previous_quantities_str = $existing_qnty;

							// New values
							$new_product_ids_str = $design_ids;
							$new_quantities_str = $qnty_array;

							// Convert the strings into arrays
							$previous_product_ids = $design_ids;
							$previous_quantities = $existing_qnty;
							$new_product_ids = $design_ids;
							$new_quantities = $qnty_array;

							// Combine product IDs and quantities into an associative array
							$previous_data = array_combine($previous_product_ids, $previous_quantities);
							$new_data = array_combine($new_product_ids, $new_quantities);

							// Calculate quantity differences
							$quantity_differences = [];
							foreach ($new_data as $product_id => $new_quantity) {
								$previous_quantity = $previous_data[$product_id] ? $previous_data[$product_id] : 0;
								$quantity_differences[$product_id] = $new_quantity - $previous_quantity;
							}
							
						// die();

						// Display or use the quantity differences
							/* foreach ($quantity_differences as $product_id => $difference) {
								echo "Product ID: $product_id, Quantity Difference: $difference\n";
							} */
							/* if ($query->num_rows()) {
								$data3_remove = array(
									'stock_qty' => (float) $row->stock_qty - (float) $value_change,
								);
								$this->db->where('p_design_number', $designs->id);
								$this->db->update('stock', $data3_remove);
							} */
						}

					foreach ($quantity_differences as $product_id => $difference) {
						// Fetch current quantity from the stock table
						// $current_quantity = $this->Stock_model->get_allstock($product_id)->stock_qty;
						// // Calculate new quantity
						// $new_quantity = $current_quantity + $difference;							
						// Update the stock table with the new quantity
						// print_r($difference);
						$this->Stock_model->update_stock($product_id, $difference);
					}
					}
				// die();
			}

			//HISTORY
			$entry_from = 4;
			$user_id = $bakers_id;
			$design = explode(',',$selected_ids);
			$qnty = $this->input->post('qnty');
			$qnty_new = implode(",", $this->input->post('qnty'));
			$data_json = array(
				'customer' => $this->input->post('customerName'),
				'customer_address' => $this->input->post('hsn'),
				'qty' => $this->input->post('cust_gst'),
			);
			$json_data = json_encode($data_json);

			for ($i = 0; $i < count($design); $i++) {
				
				$product_id = $design[$i];
				$quantity = $qnty[$i];
				$in_out_qty = -1 * $quantity;
				$this->db->where('p_design_number', $design[$i]);
				$query = $this->db->get('stock');
				$previous_qty = $query->row();
				$current_qty = $previous_qty->stock_qty;
	
				$this->History_model->updateHistoryRecordByInvoiceId($entry_from, $user_id, $invoice_no, $product_id, $in_out_qty, $current_qty, $json_data);
			}

			$data = array(
				'customer_id' => $bakers_id,
				'invoice_no' => $invoice_no,
				'customer_address' => $this->input->post('cust_adds_txt'),
				'product_name' => $material,
				'hsn' => $selected_ids,
				'qnty' => implode(",", $this->input->post('qnty')),
				'rate' => $rate,
				'amount' => $amount,
				'total' => $total_amount,
				'total_in_words' => $total_word,
				'paid' => $paid_amount,
				'balance' => $balance_amount,
				'invoice_date' => $date
			);

			
			$data_ledger = array(
				'customer' => $bakers_id,
				'invoice' => $invoice_no,
				'quantity' => implode(",", $this->input->post('qnty')),
				'rate' => $rate,
				'bill_amount' => $total_amount,
				'paid_amount' => $paid_amount,
				'last_amount' => $balance_amount,
				'dated' => $date
			);

			$this->db->where('invoice_no', $invoice_no);
			$insert = $this->db->update('insider_bill', $data);

			$ledge_insert = $this->Balance_model->update_ledgerbalance($data_ledger, $bakers_id, $invoice_no);


			$this->db->select('*');
			$this->db->from('customers');
			$this->db->where('id', $bakers_id);
			$query = $this->db->get();
			$customers_name = $query->row();
			$data_pdf = array(
				'customer' => $customers_name->name,
				'customer_address' => $this->input->post('cust_adds_txt'),
				'gst' => $this->input->post('cust_gst'),
				'invoice_no' => $invoice_no,
				'product_name' => $material,
				'hsn' => $hsns,
				'qnty' => $qnty_new,
				'rate' => $rate,
				'amount' => $amount,
				'total' => $total_amount,
				'round_off_total' => $total_round,
				'total_in_words' => $total_word,
				'paid' => $paid_amount,
				'balance' => $balance_amount,
				'qnty_sum' => $qnty_sum,
				'date' => $date,
				'igst_5_cent' => '',
				'cgst_2_5_cent' => '',
			);


			if ($insert == true) {
				if ($this->db->trans_status() === false) {
					// Handle transaction failure
					$this->session->set_flashdata('success', 'Stock Updated successfully....');
				}
				$this->load->library('tcpdf/tcpdf.php');

				$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				$pdf->setPrintHeader(false);
				$pdf->setPrintFooter(false);
				$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT, true);
				//$pdf->SetFont('helvetica', '', 10);
				$pdf->SetFont('times', '', 10);
				$pdf_data = $this->load->view('invoice_pdf', $data_pdf, true);
				$pdf->addPage();
				$pdf->writeHTML($pdf_data, true, false, true, false, '');

				$filename = $this->input->post('invoice_no') . '.pdf';
				// print_r($data_pdf['customer']);
				// die();
				$dir = APPPATH . '/invoice/' . $customers_name->id . '/';

				if (!is_dir($dir)) {
					mkdir($dir, 0777, true);
				}
				$save_path = $dir . $filename;
				ob_end_clean();
				// $pdf->Output($save_path, 'I');
				$pdf->Output($save_path, 'F');
				//file_put_contents($save_path, $pdf);
				$this->session->set_flashdata('success', 'Invoice created successfully....');
				redirect('Invoice/');
			} else {
				$this->session->set_flashdata('fail', "Sorry! there was some error.");
				redirect(base_url('/index.php/Invoice/create'));
			}
		}
	}
	public function edit_sell($sell_id)
	{
		// echo $sell_id;
		if ($this->session->userdata('logged_in')) {
			$data['title'] = 'Edit Sell Invoice';
			$data['username'] = $this->session->userdata('logged_in');
			$data['custList'] = $this->Challan_model->get_all_customer();
			$data['productList'] = $this->Challan_model->get_all_products();
			$data['last_invoice'] = $this->Challan_model->get_last_invoice_insider();
			$data['designs'] = $this->Design_model->get_all_design();
			$data['sell_stock'] = $this->Challan_model->get_sell_stock($sell_id);
			$this->load->view('layout/header', $data);
			$this->load->view('layout/menubar');
			$this->load->view('sell_edit', $data);
			$this->load->view('layout/footer');
		} else {
			redirect('Welcome');
		}
	}
	//Download pdf invoice
	public function download_pdf($cust_name, $invoice_id)
	{

		if (!$this->session->userdata('logged_in')) {
			redirect('Welcome');
		} elseif ($cust_name && $invoice_id) {
			$pdf_file = APPPATH . 'invoice/' . rawurldecode($cust_name) . '/' . $invoice_id . '.pdf';
			$file = $invoice_id . '.pdf';

			if (file_exists($pdf_file)) {
				header("Content-Type: application/pdf");
				header("Content-Disposition: attachment;filename=\"$file\"");
				readfile($pdf_file);
			} else {
				$this->session->set_flashdata('no_pdf', 'Sorry! file not found...');
				redirect('Invoice');
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
	// Logout from admin page//
	public function logout()
	{
		$this->session->unset_userdata('logged_in');
		header("location:" . site_url('?status=loggedout'));
	}

	public function deleteInvoice()
	{
		$customer_name = $this->input->post('customer_name');
		$invoice_number = $this->input->post('invoice_number');

		$pdf_file = APPPATH . 'invoice/' . $customer_name . '/' . $invoice_number . '.pdf';
		//print_r($pdf_file);
		/* if($this->input->post(' '))
			  { */
		$id = $this->input->post('row_id');
		//$upd = 1; //$this->Challan_model->delete_invoice_by_id($id);
		//print_r($id);
		$upd = $this->Challan_model->delete_invoice_by_id($id);
		if ($upd) {
			if (file_exists($pdf_file)) {
				unlink($pdf_file);
			}
			$response['result'] = 'Invoice deleted successfully.';
			$response['status'] = 'passed';
		} else {
			$response['result'] = 'Sorry there was some error';
			$response['status'] = 'failed';
		}
		echo json_encode($response);
		//}
	}
	public function StockById()
	{
		$id = $this->input->post('design_id');
		$data = $this->Stock_model->get_allstock($id);
		echo json_encode($data);
	}

}