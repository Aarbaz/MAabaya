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
		$this->load->model('History_model');
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
		// $this->form_validation->set_rules('rate[]', 'Rate', 'required');
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

			// $rate = implode(',', $this->input->post('rate[]'));
			// $rate = trim($rate, ',');

			$total_material_used = implode(',', $this->input->post('total_material_used'));
			$total_material_used = trim($total_material_used, ',');

			// $material_used_array = explode(",", $total_material_used);

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
					'average' => $this->input->post('rate_' . $i . '[]'),
					'customer_id' => $this->input->post('customerName'),
					'invoice_no' => $this->input->post('invoice_no'),
					'labour_charge' => $this->input->post('karigari_' . $i . '[]'),
					'total_karigari' => $this->input->post('total_karigari_' . $i . '[]'),
					'total' => $total_amount,
					'round_off_total' => $total_round,
					'total_in_words' => $total_word,
					'paid' => $paid_amount,
					'balance' => $balance_amount,
				);
				// Add the new design to the result array
				$result[] = $design;
				$idSubArray = $this->input->post('items_' . $i . '[]');
				$valueSubArray = $this->input->post('total_material_' . $i . '[]');

				// Loop through the sub-array and add its elements to $mat_id
				foreach ($idSubArray as $value) {
					$mat_id[] = $value;
				}
				foreach ($valueSubArray as $value2) {
					$mat_values[] = $value2;
				}
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
					'material_used' => $row['total_material'][0],
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
				$materialused = $materialData[0]['quantity'];
				$all_material_ids_array = explode(",", $materialId2);
				$material_used_array = explode(",", $materialused);

				$m = 0;
				$k++;
			}
			if ($this->input->post('bill_date')) {
				$date = $this->input->post('bill_date');
			} else {
				$date = date("Y-m-d");
			}
			$json_data = array(
				'data_json' => $json,
				'master_id' => $this->input->post('customerName'),
				'invoice_no' => $this->input->post('invoice_no'),
				'type' => 'new',
				'created_at' => $date,
			);
			/***************** Material Stock Update  *********************/
			for ($i = 0; $i < count($mat_id); $i++) {

				// Check if the material ID exists in the maker_stock table for the customer ID
				$this->db->where('materials_id', $mat_id[$i]);
				//$this->db->where('making_owner_id', $customer_id);
				$q_result = $this->db->get('maker_stock')->result();
				// print_r($q_result);die();
				if (empty($q_result) || count($q_result) == 0) {
					// Insert a new row with the material ID and quantity 0
					$m_data = array(
						'materials_id' => $mat_id[$i],
						//'making_owner_id' => $customer_id,
						'quantity' => $mat_values[$i]
					);
					$this->db->insert('maker_stock', $m_data);
				} else {
					// Get the previous quantity for the material ID
					$material_ids = $mat_id[$i];
					$this->db->where_in('materials_id', $material_ids);
					//$this->db->where('making_owner_id', $customer_id);
					$prev_quantity = $this->db->get('maker_stock')->row()->quantity;
					// Update the quantity for the material ID with the previous quantity + new quantity
					$data = array('quantity' => (float) $prev_quantity - (float) $mat_values[$i]);

					$this->db->where('materials_id', $mat_id[$i]);
					//$this->db->where('making_owner_id', $customer_id);
					$this->db->update('maker_stock', $data);
				}

			}
			/********************Material Stock Update end**********************/
			$insert = $this->db->insert('product_pices', $json_data);


			if ($insert == true) {

				/***************** Pices Stock Update  *********************/
				$entry_from = 3;
				$user_id = $this->input->post('customerName');
				$invoice_id = $this->input->post('invoice_no');
				$data_json = $json;
				foreach ($data2 as $row) {
					$product_id = $row['p_design_number'];
					$quantity = $row['stock_qty'];

					$this->db->where('p_design_number', $product_id);
					$query = $this->db->get('stock');
					$row = $query->row();
					if ($query->num_rows()) {
						// If the product exists, update the quantity value in the database
						$data3 = array(
							'stock_qty' => (float) $row->stock_qty + (float) $quantity
						);
						//print_r($data3);
						$this->db->where('p_design_number', $product_id);
						$this->db->update('stock', $data3);
					} else {
						// If the product does not exist, insert a new row into the database
						$this->db->insert('stock', array('p_design_number' => $product_id, 'stock_qty' => $quantity));
					}
					$in_out_qnty = $quantity;
					$current_stock = $this->db->where('p_design_number', $product_id);
					$query = $this->db->get('stock');
					$row = $query->row();
					$current_stock_value = $row->stock_qty;
					$stock = $current_stock_value ? $current_stock_value : $in_out_qnty;
					$this->History_model->insertStockEntry($entry_from, $user_id, $invoice_id, $product_id, $in_out_qnty, $stock, $data_json);
				}

				/***************** Pices Stock Update end ******************/


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
				/* $json_data_array = array(
											'entry_from' => 3,
											//Pices
											'json_data' => $json,
										);

										$insert_json_data = $this->Pices_model->create_history($json_data_array); */
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
					'customer_id' => $customer_id,
					'customer' => $maker_name->name,
					'product_name' => $material_names,
					// 'hsn' => $hsn,
					'qnty' => $qnty,
					'invoice_no' => $this->input->post('invoice_no'),
					'customer_address' => $maker_name->address,
					'json_data' => $json_data,
					'date' => $date,
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
				$dir = APPPATH . '/pices_invoice/' . $data_pdf['customer_id'] . '/';
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
		$cust_data = $this->Pices_model->get_pices_byID($pices_id);

		if (!$this->session->userdata('logged_in')) {
			redirect('Welcome');
		} else {
			$postData = $this->input->post();

			$this->form_validation->set_rules('customerName', 'customer Name', 'required');
			//$this->form_validation->set_rules('amount[]', 'Total Material', 'required');

			if ($this->form_validation->run() == false) {
				$data['title'] = 'Edit Pices Details';
				$data['username'] = $this->session->userdata('logged_in');
				$data['cust'] = $cust_data;
				$data["custList"] = $this->Customer_model->get_mowner();
				$data['materialList'] = $this->Purchaser_model->get_all_material();
				$data['designs'] = $this->Design_model->get_all_design();
				$this->load->view('layout/header', $data);
				$this->load->view('layout/menubar');
				$this->load->view('pices_edit');
				$this->load->view('layout/footer');
			} else {

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
				$customer_id = $this->input->post('customerName');
				$sr_no = $this->input->post('sr_no');
				$invoice_no = $this->input->post('invoice_no');

				$total_amount = $this->input->post('total_amount');
				$paid_amount = $this->input->post('paid_amount');
				$balance_amount = $this->input->post('balance_amount');
				
				/********************Customer Balance Update**********************/
				$get_ledger_invoice = $this->Balance_model->get_bal_user_bill($invoice_no);

                $ledger_bill = $get_ledger_invoice->bill_amount;
                $paidBill = $get_ledger_invoice->paid_amount;
                $ledger_last = $get_ledger_invoice->last_amount;
				$value_null = '0';
				if ($balance_amount) {
                    $this->db->where('customer_id', $customer_id);
                    $this->db->where('bill_no', $invoice_no);
                    $query = $this->db->get('balance');
                    $row = $query->row();
                    if ($query->num_rows()) {


                        if ($paidBill) {
                            $diff_paid = (float) $paid_amount - (float) $paidBill;

                        } else {
                            $diff_paid = (float) $paid_amount - (float) $value_null;
                        }

                        if ($diff_paid > 0) {
                            // echo "The diff_paid is positive: " . $diff_paid;   
                            $paid_bill_new = (float) $row->paid_bill + (float) $diff_paid;
                        } elseif ($diff_paid < 0) {
                            $paid_bill_new = (float) $row->paid_bill - (float) abs($diff_paid);
                        } else {
                            $paid_bill_new = (float) $row->paid_bill + (float) $diff_paid;
                        }

                        if ($ledger_bill) {
                            $diff_total = (float) $total_amount - (float) $ledger_bill;


                        } else {

                            $diff_total = (float) $total_amount - (float) $value_null;
                        }


                        if ($diff_total > 0) {
                            $total_bill_new = (float) $row->total_bill + (float) $diff_total;
                        } elseif ($diff_total < 0) {
                            $total_bill_new = (float) $row->total_bill - (float) abs($diff_total);
                        } else {
                            $total_bill_new = (float) $row->total_bill + (float) $diff_total;
                        }




                        if ($ledger_last) {
                            $diff_bal = (float) $balance_amount - (float) $ledger_last;

                        } else {
                            $diff_bal = (float) $balance_amount - (float) $value_null;
                        }

                        if ($diff_bal > 0) {
                            // echo "The diff_balerence is positive: " . $diff_bal; 
                            $bal_bill_new = (float) $row->balance_bill + (float) $diff_bal;

                        } elseif ($diff_bal < 0) {
                            // echo "The diff_balerence is negative: " . abs($diff_bal);
                            $bal_bill_new = (float) $row->balance_bill - (float) abs($diff_bal);
                        } else {
                            $bal_bill_new = (float) $row->balance_bill + (float) $diff_bal;
                        }

                        $data3 = array(
                            'customer_id' => $customer_id,
                            'bill_no' => $invoice_no,
                            'total_bill' => $total_bill_new,
                            'paid_bill' => $paid_bill_new,
                            'balance_bill' => $bal_bill_new,
                            'updated_on' => date('Y-m-d H:i:s')
                        );
                        $bal_update = $this->Balance_model->update_balanceBybill($data3, $customer_id, $invoice_no);
					}
				}
				
				/********************Customer Balance Update end**********************/
				$total_round = $this->input->post('total_round');
				$total_word = $this->input->post('total_word');
				$steps = $this->input->post('steps');
				// Loop through each design number
				for ($i = 0; $i < $steps; $i++) {
					// Create a new array for this design
					$design = array(
						'design_number' => $this->input->post('hsn_' . $i . '[]'),
						'materials_ids' => $this->input->post('items_' . $i . '[]'),
						'total_material' => $this->input->post('total_material_' . $i . '[]'),
						'total_piece' => $this->input->post('total_piece_' . $i . '[]'),
						'average' => $this->input->post('rate_' . $i . '[]'),
						'customer_id' => $this->input->post('customerName'),
						'invoice_no' => $this->input->post('invoice_no'),
						'labour_charge' => $this->input->post('karigari_' . $i . '[]'),
						'total_karigari' => $this->input->post('total_karigari_' . $i . '[]'),
						'total' => $total_amount,
						'round_off_total' => $total_round,
						'total_in_words' => $total_word,
						'paid' => $paid_amount,
						'balance' => $balance_amount,
					);
					// Add the new design to the result array
					$result[] = $design;
					$idSubArray = $this->input->post('items_' . $i . '[]');
					// Loop through the sub-array and add its elements to $mat_id
					foreach ($idSubArray as $value) {
						$mat_id[] = $value;
					}
						
				}
				// Retrieve the existing record from the database
				$existingData = $this->Pices_model->get_pices_by_id($sr_no); // Replace 'get_data_by_id' with the actual method in your model to retrieve the existing data

				if ($existingData) {
					// Decode the JSON from the data_json field
					$jsonData = json_decode($existingData['data_json'], true);
					$materials_ids = array();
					$total_materials = array();
						$f = 0;
					foreach ($jsonData as $item) {
						// print_r($item['materials_ids']);
						if (isset($item['materials_ids']) && isset($item['total_material'])) {
							$material_ids = $item['materials_ids'];
							$total_material = $item['total_material'];

							$materials_ids = array_merge($materials_ids, $material_ids);
							$total_materials = array_merge($total_materials, $total_material);
						}
						$f++;
					}
					
					$diffMaterials = []; // Initialize an array to store the different materials and their total_material values

					foreach ($jsonData as $item) {
						if (isset($item['materials_ids']) && isset($item['total_material'])) {
							$material_ids = $item['materials_ids'];
							$total_material = $item['total_material'];

							foreach ($material_ids as $index => $material_id) {
								if (in_array($material_id, $mat_id)) {
									// Material is in $mat_id, so it's not different
									continue;
								}
								// Material is different, add it to the $diffMaterials array with both key and value
								$diffMaterials[$material_id] = $total_material[$index];
							}
						}
					}
					foreach ($diffMaterials as $material_id => $total_material) {
						// echo "Material ID: $material_id, Total Material: $total_material<br>";
						$this->db->where('materials_id', $material_id);
						$queryrow_gr = $this->db->get('maker_stock');
						$row_gr = $queryrow_gr->row();

						if ($queryrow_gr->num_rows()) {

							$data3_gr = array(
								'quantity' => (float) $row_gr->quantity + (float) $total_material,
							);
							$this->db->where('materials_id', $material_id);
							$this->db->update('maker_stock', $data3_gr);
						}
						else{
							$this->db->where('materials_id', $material_id);
							$s_result = $this->db->get('maker_stock')->result();
							if (empty($s_result) || count($s_result) == 0) {
								// Insert a new row with the material ID and quantity 0
								$s_data = array(
									'materials_id' => $material_id,
									'quantity' => $total_material
								);
								$this->db->insert('maker_stock', $s_data);
							}
						}
					}
				}
				
				$balance_value = '0';
				$resultArray = array();

				// Assuming you have a for loop
				for ($i = 0; $i < $steps; $i++) {
					// Inside the loop, get the data for each iteration
					$valueSubArray = $this->input->post('total_material_' . $i . '[]');
					$valueSubArrayHidden = $this->input->post('total_material_hidden_' . $i . '[]');

					// Check if both arrays have values before performing the subtraction
					// if (isset($valueSubArray) && isset($valueSubArrayHidden)) {
					if (isset($valueSubArrayHidden)) {
						// Make sure both arrays have the same number of elements
						if (count($valueSubArray) == count($valueSubArrayHidden)) {
							// Perform the subtraction and accumulate the results in $resultArray
							for ($index = 0; $index < count($valueSubArrayHidden); $index++) {
								if($valueSubArrayHidden[$index]){
									$resultArray[] = (float)$valueSubArray[$index] - (float)$valueSubArrayHidden[$index];
								}
								else{
									$resultArray[] = (float)$valueSubArray[$index] - (float)$balance_value;
								}

							}
						} else {
							for ($index = 0; $index < count($valueSubArrayHidden); $index++) {
								$resultArray[] = $valueSubArray[$index] - $balance_value;
							}
						}
					} else if (isset($valueSubArray)) {
						// Perform the subtraction and accumulate the results in $resultArray
						for ($index = 0; $index < count($valueSubArray); $index++) {
							$resultArray[] = $valueSubArray[$index] - $balance_value;
						}

					}
				}
				
				$resultArray_j = array();

				// Assuming you have a for loop
				for ($j = 0; $j < $steps; $j++) {
					// Inside the loop, get the data for each iteration
					$valueSubArray_j = $this->input->post('total_piece_' . $j . '[]');
					$valueSubArrayHidden_j = $this->input->post('total_piece_hidden_' . $j . '[]');

					// Check if both arrays have values before performing the subtraction
					if (isset($valueSubArray_j)) {
						if (isset($valueSubArrayHidden_j)) {
							// Make sure both arrays have the same number of elements
							if (count($valueSubArray_j) == count($valueSubArrayHidden_j)) {
								// Perform the subtraction and accumulate the results in $resultArray_j
								for ($index_j = 0; $index_j < count($valueSubArrayHidden_j); $index_j++) {

									if($valueSubArrayHidden_j){
										$resultArray_j[] = $valueSubArray_j[$index_j] - $valueSubArrayHidden_j[$index_j];
									}
									else{
										$resultArray_j[] = $valueSubArray_j[$index_j] - $balance_value;		
									}
								}
							} else {
								for ($index_j = 0; $index_j < count($valueSubArrayHidden_j); $index_j++) {
									$resultArray_j[] = $valueSubArray_j[$index_j] - $balance_value;
								}

							}
						} else if (isset($valueSubArray_j)) {
							for ($index_j = 0; $index_j < count($valueSubArray_j); $index_j++) {
								$resultArray_j[] = $valueSubArray_j[$index_j] - $balance_value;
							}
						}
					}
				}
				$json = json_encode($result);
				$data = $json;
				$k = 0;
				// $array = json_decode($data, true);
				foreach ($result as $row) {
					$insert_data = [
						'design_number' => $row['design_number'][0],
						'material_id' => implode(',', $row['materials_ids']),
						'material_used' => implode(',', $row['total_material']),
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
					// $materialused = $materialData[0]['quantity'];
					$k++;
				}
				if ($this->input->post('bill_date')) {
					$date = $this->input->post('bill_date');
				} else {
					$date = date("Y-m-d");
				}
				$json_data = array(
					'data_json' => $data,
					'master_id' => $this->input->post('customerName'),
					'invoice_no' => $this->input->post('invoice_no'),
					'type' => 'new',
					'created_at' => $date,
				);

				/***************** Material Stock Update  *********************/
				for ($i = 0; $i < count($mat_id); $i++) {
					// Check if the material ID exists in the maker_stock table for the customer ID
					$this->db->where('materials_id', $mat_id[$i]);
					$q_result = $this->db->get('maker_stock')->result();
					if (empty($q_result) || count($q_result) == 0) {
						// Insert a new row with the material ID and quantity 0
						$m_data = array(
							'materials_id' => $mat_id[$i],
							'quantity' => $resultArray[$i]
						);
						$this->db->insert('maker_stock', $m_data);
					} else {
						// Get the previous quantity for the material ID
						$material_ids = $mat_id[$i];
						$this->db->where_in('materials_id', $material_ids);
						$prev_quantity = $this->db->get('maker_stock')->row()->quantity;
						// Update the quantity for the material ID with the previous quantity - diff quantity
						$data = array('quantity' => (float) $prev_quantity - (float) $resultArray[$i]);
						$this->db->where('materials_id', $mat_id[$i]);
						$this->db->update('maker_stock', $data);
					}

				}
				/********************Material Stock Update end**********************/

				$this->db->where('sr_no', $sr_no);
				$this->db->where('master_id', $customer_id);
				$insert = $this->db->update('product_pices', $json_data);
				if ($insert == true) {
					/***************** Pices Stock Update  *********************/
					//$this->History_model->deletHistoryByMakerInvoiceId($invoice_no);

					$r = 0;
					foreach ($data2 as $row) {
						$entry_from = '3';
						$user_id = $this->input->post('customerName');
						$invoice_id = $this->input->post('invoice_no');
						$product_id = $row['p_design_number'];
						// $quantity = $row['stock_qty'];

						$this->db->where('p_design_number', $product_id);
						$query = $this->db->get('stock');
						$row = $query->row();
						if ($query->num_rows()) {
							// If the product exists, update the quantity value in the database
							$data3 = array(
								'stock_qty' => (float) $row->stock_qty + (float) $resultArray_j[$r]
							);
							$this->db->where('p_design_number', $product_id);
							$this->db->update('stock', $data3);
						} else {
							// If the product does not exist, insert a new row into the database
							$this->db->insert('stock', array('p_design_number' => $product_id, 'stock_qty' => $resultArray_j[$r]));
						}
						$in_out_qnty = $resultArray_j[$r];

						//$this->History_model->insertStockEntry($entry_from, $user_id, $invoice_no, $product_id, $in_out_qnty, $updated_stock, $json);
						$r++;
					}

					/***************** Pices Stock Update end ******************/
					for($m=0; $m < $step; $m++){
						$entry_from = '3';
						$user_id = $this->input->post('customerName');
						$invoice_id = $this->input->post('invoice_no');
						$product_id = $this->input->post('hsn_' . $m . '[]');
						$current_stock = $this->db->where('p_design_number', $product_id);
						$query = $this->db->get('stock');
						$row = $query->row();
						$current_stock_value = $row->stock_qty;
						$updated_stock = $current_stock_value ? $current_stock_value : $in_out_qnty;
						$this->History_model->updateHistoryRecordByInvoiceId($entry_from, $user_id, $invoice_id, $product_id, $in_out_qnty, $current_stock_value, $json);
					}
					/********************Customer Ledger Balance (History) ***************/
					$data_ledger = array(
						'customer' => $this->input->post('customerName'),
						'invoice' => $invoice_no,
						'paid_amount' => $paid_amount,
						'bill_amount' => $total_amount,
						'last_amount' => $balance_amount,
						'entry_from' => 3,
						'dated' => date('Y-m-d H:i:s')
					);
					$insert = $this->Balance_model->update_ledgerbalance($data_ledger, $customer_id, $invoice_no);
					/********************Customer Ledger Balance (History) end**************/

					/********************Add In History Table     ****************/
					$steps = $this->input->post('steps');
					for($mk=0; $mk < $steps; $mk++){
						$entry_from = '3';
						$user_id = $this->input->post('customerName');
						$invoice_id = $this->input->post('invoice_no');
						$product_id = $this->input->post('hsn_'.$mk.'[]');
						$in_out_qnty = $this->input->post('total_piece_'.$mk.'[]');
						$current_stock = $this->db->where('p_design_number', $product_id[0]);
						$query = $this->db->get('stock');
						$row = $query->row();
						$current_stock_value = $row->stock_qty;
						$updated_stock = $current_stock_value ? $current_stock_value : $in_out_qnty[0];
						$this->History_model->updateHistoryRecordByInvoiceId($entry_from, $user_id, $invoice_id, $product_id[0], $in_out_qnty[0], $current_stock_value, "");
					}
					/********************AAdd In History Table end**************/

					$this->db->select('*');
					$this->db->from('customers');
					$this->db->where('id', $customer_id);
					$query = $this->db->get();
					$maker_name = $query->row();

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
						'customer_id' => $customer_id,
						'customer' => $maker_name->name,
						'product_name' => $material_names,
						'qnty' => $qnty,
						'invoice_no' => $this->input->post('invoice_no'),
						'customer_address' => $maker_name->address,
						'json_data' => $json_data,
						'date' => $date,
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
					$dir = APPPATH . '/pices_invoice/' . $data_pdf['customer_id'] . '/';
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


		$this->form_validation->set_rules('customerName', 'Customer Name', 'trim|required');
		$this->form_validation->set_rules('frm_mth', 'From Month', 'trim|required');
		$this->form_validation->set_rules('frm_yr', 'From Year', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$response['result'] = 'Please select customer, month and year.';
			$response['status'] = 'failed';
			//echo json_encode($response);
		}
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

				header('Content-type: application/pdf');
				header('Content-Disposition: attachment; filename="' . $file . '"');
				header('Content-Transfer-Encoding: binary');
				header('Content-Length: ' . filesize($pdf_file));
				header('Accept-Ranges: bytes');
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
				// $response['path'] = json_encode($save_path);

				$this->session->set_flashdata('success', 'PDF generated successfully....');
				sleep(4);
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
			$return_invoice_no = $this->input->post('return_invoice_no');
			$design_nos = implode(',', $this->input->post('design_no[]'));
			$design_nos = trim($design_nos, ',');

			$return_qntys = implode(',', $this->input->post('return_qnty[]'));
			$return_qntys = trim($return_qntys, ',');

			$data = $this->input->post();
			$input_array = array(
				'design_number' => $this->input->post('design_no[]'),
				'customer_id' => $customerName,
				'return_invoice_no' => $return_invoice_no,
				'total_piece' => $this->input->post('return_qnty[]'),
			);

			$output_array = array();

			for ($i = 0; $i < count($input_array['design_number']); $i++) {
				$item = array(
					"design_number" => [$input_array['design_number'][$i]],
					"customer_id" => $input_array['customer_id'],
					"return_invoice_no" => $input_array['return_invoice_no'],
					"total_piece" => [$input_array['total_piece'][$i]]
				);

				$output_array[] = $item;
			}
			if ($this->input->post('bill_date')) {
				$date = $this->input->post('bill_date');
			} else {
				$date = date("Y-m-d");
			}
			$design_json = json_encode($output_array);
			$json_data = array(
				'data_json' => $design_json,
				'master_id' => $customerName,
				'invoice_no' => $return_invoice_no,
				'type' => 'gr',
				'created_at' => $date,
			);

			$entry_from = 5;
			$user_id = $this->input->post('customerName');
			$invoice_id = $this->input->post('return_invoice_no');
			$data_json = $design_json;

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
				$in_out_qnty = $return_qnty;
				$current_stock = $this->db->where('p_design_number', $design_no);
				$query = $this->db->get('stock');
				$row = $query->row();
				$current_stock_value = $row->stock_qty;
				$stock = $current_stock_value ? $current_stock_value : $in_out_qnty;
				$this->History_model->insertStockEntry($entry_from, $user_id, $invoice_id, $design_no, $in_out_qnty, $stock, $data_json);
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
					'customer_id' => $customerName,
					'customer' => $username,
					'design_no' => $products,
					'qnty' => $return_qntys,
					'return_invoice_no' => $return_invoice_no,
					'date' => $date,
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

				$filename = $return_invoice_no . '.pdf';
				$dir = APPPATH . '/pices_invoice/' . $data_pdf['customer_id'] . '/';
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

	public function returnEditPices($sr_no)
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
			// $sr_no = $this->input->post('sr_no');
			$design_nos = implode(',', $this->input->post('design_no[]'));
			$design_nos = trim($design_nos, ',');

			$return_qntys = implode(',', $this->input->post('return_qnty[]'));
			$return_qntys = trim($return_qntys, ',');

			$return_qntys_hidden = implode(',', $this->input->post('return_qnty_hidden[]'));
			$return_qntys_hidden = trim($return_qntys_hidden, ',');

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


			if ($this->input->post('bill_date')) {
				$date = $this->input->post('bill_date');
			} else {
				$date = date("Y-m-d");
			}
			$design_json = json_encode($output_array);
			$json_data = array(
				'data_json' => $design_json,
				'master_id' => $customerName,
				'invoice_no' => $invoice_no,
				'type' => 'gr',
				'created_at' => $date,
			);
			// print_r($sr_no);
			// die();
			// $insert = $this->db->update_records('product_pices', $json_data, $sr_no);
			// $db_data = $this->Pices_model->update_records($json_data, $sr_no);

			// Retrieve the existing record from the database
			$existingData = $this->Pices_model->get_pices_by_id($sr_no); // Replace 'get_data_by_id' with the actual method in your model to retrieve the existing data

			if ($existingData) {

				// Decode the JSON from the data_json field
				$jsonData = json_decode($existingData['data_json'], true);
				$designNumbers = [];
				$stk_qty = [];

				// Loop through each item in the decoded JSON data
				foreach ($jsonData as $item) {
					if (isset($item['design_number']) && is_array($item['design_number']) && count($item['design_number']) > 0) {
						$designNumbers[] = $item['design_number'][0];
						$stk_qty[] = $item['total_piece'][0];
					}
				}

				$oldDesign = implode(',', $this->input->post('design_no[]'));

				$valuesArray = array_map('trim', explode(',', $oldDesign));

				// Combine design numbers with their corresponding stock quantities
				$designStockMap = array_combine($designNumbers, $stk_qty);

				// Convert the array string into an actual array
				$one = $designNumbers;
				// Find the values in $oneString that are not in $valuesArray
				$valuesNotInArray = array_diff($one, $valuesArray);

				foreach ($valuesNotInArray as $value) {
					if (isset($designStockMap[$value])) {
						$stockToSubtract = $designStockMap[$value];
						// echo "Design number $value does not exist in the valuesToCheck list. Stock subtracted from the table: $stockToSubtract.\n";

						$this->db->where('p_design_number', $value);
						$queryrow_gr = $this->db->get('stock');
						$row_gr = $queryrow_gr->row();

						if ($queryrow_gr->num_rows()) {

							$data3_gr = array(
								'stock_qty' => (float) $row_gr->stock_qty - (float) $stockToSubtract,
							);

							// print_r($data3_gr);
							// print_r($stockToSubtract);

							$this->db->where('p_design_number', $value);
							$this->db->update('stock', $data3_gr);
							// print_r($this->db->last_query());
							// die();
						} else {
							// print_r('fhdfg');
							// echo "Design number $value does not have a corresponding stock quantity. No stock subtraction performed.\n";
						}
					}
				}

			}
			$db_data = $this->Pices_model->update_records($json_data, $sr_no);
			foreach ($data['design_no'] as $index => $design_no) {
				$return_qnty = $data['return_qnty'][$index];
				$return_qnty_hidden = $data['return_qnty_hidden'][$index];
				// Check if design_no exists in the table
				$existing_record = $this->db->get_where('stock', array('p_design_number' => $design_no))->row();
				if ($existing_record) {

					$value_null = '0';
					if ($return_qnty_hidden) {
						$diff = (float) $return_qnty - (float) $return_qnty_hidden;

					} else {

						$diff = (float) $return_qnty - (float) $value_null;
					}

					if ($diff > 0) {
						// If the product exists, update the quantity value in the database
						$data3 = array(
							'stock_qty' => (float) $existing_record->stock_qty + (float) $diff,
						);
					} elseif ($diff < 0) {

						// echo "The difference is negative: " . abs($diff);
						$data3 = array(
							'stock_qty' => (float) $existing_record->stock_qty - (float) $diff,
						);
					} else {
						$data3 = array(
							'stock_qty' => (float) $existing_record->stock_qty + (float) abs($diff),
						);
					}
					$this->db->where('p_design_number', $design_no);
					$this->db->update('stock', $data3);


				} else {

					// Design number doesn't exist, insert new record
					$result = $this->db->insert('stock', array('p_design_number' => $design_no, 'stock_qty' => (float) $return_qnty));

				}
			}
			// die();

			if ($db_data) {
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
				// HISTORY

				$totalDesigns = $this->input->post('design_no[]');
				$totalDesignQty = $this->input->post('return_qnty[]');
                $this->History_model->deletHistoryByMakerInvoiceId($invoice_no);
				for ($i = 0; $i < count($totalDesigns); $i++) {

					$entry_from = 5;
					$user_id = $this->input->post('customerName');
					$invoice_id = $this->input->post('invoice_no');
					$design_id = $totalDesigns[$i];
					$in_out_qnty = $totalDesignQty[$i];

					$current_stock = $this->db->where('p_design_number', $design_id);
					$query = $this->db->get('stock');
					$row = $query->row();
					$current_stock_value = $row->stock_qty;


					$historyData = $this->History_model->insertStockEntry($entry_from, $user_id, $invoice_id, $design_id, $in_out_qnty, $current_stock_value, $design_json);
				}



				$products = implode(',', $product_names);

				$data_pdf = [
					'customer_id' => $customerName,
					'customer' => $username,
					'design_no' => $products,
					'qnty' => $return_qntys,
					'invoice_no' => $invoice_no,
					'date' => $date,
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
				$dir = APPPATH . '/pices_invoice/' . $data_pdf['customer_id'] . '/';
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