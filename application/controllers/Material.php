<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Material extends CI_Controller {
	/*
	 1. index() is by default called to display material list
	 2. add_new() is called to display form and store new entry into DB
	 3. edit() is called to update single material
	*/

	public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Material_model');
        $this->load->model('Purchaser_model');
        $this->load->model('Vendor_model');
    }


		public function index()
		{
			if($this->session->userdata('logged_in'))
	        {
	        	$data['title'] = ucfirst('Material List');
	        	$data['username'] = $this->session->userdata('logged_in');
	        	$data['materials'] = $this->Purchaser_model->get_all_material();
	        	// $data['vendors'] = $this->Vendor_model->get_all_vendors();
		        $this->load->view('layout/header', $data);
		        $this->load->view('layout/menubar');
				$this->load->view('materialList', $data);
				$this->load->view('layout/footer');
			}
			else
			{
				redirect('welcome');
			}
		}


		public function fetch_by_id($id){
				$id = $this->Purchaser_model->get_material_byID($id);
		}

		public function deleteMaterial()
	{
		if( $this->input->post('id'))
		{
			$id = $this->input->post('id');
			$upd = $this->Purchaser_model->delete_by_mid($id);
			if($upd > 0)
			{
				$resp['status'] = 'passed';
				$resp['result'] = 'Material deleted successfully.';
			}
			else
			{
				$resp['status'] = 'failed';
				$resp['result'] = 'Some problem occurred, please try again';
			}
			echo json_encode($resp);
		}
	}


	public function edit_material(){

			$this->form_validation->set_rules('material_name', 'Material name', 'required');
			if ($this->form_validation->run() == false)
	{
		$this->index();
	}
	else
	{
					$material_name = $this->input->post('material_name');
					$id = $this->input->post('id');
					$data = array(
							'material_name' => $material_name,
					);
					if ($id) {
							$data = array(
									'id' => $id,
									'material_name' => $material_name,
							);
							$insert = $this->Purchaser_model->update_record($id,$data);
					}
					else{
							$insert = $this->Purchaser_model->create_material($data);
					}

					if($insert == true)
		{
							$this->session->set_flashdata('success', 'Updated successfully....');
			redirect('Material/');
		}
		else
		{
			$this->session->set_flashdata('fail', "Sorry! there was some error.");
			redirect(base_url('/index.php/Material'));
					}
			}
	}

	// public function index()
	// {
	// 	if($this->session->userdata('logged_in'))
  //       {
  //       	$data['title'] = ucfirst('Material List');
  //       	$data['username'] = $this->session->userdata('logged_in');
  //       	$data['materials'] = $this->Material_model->get_all_material();
  //       	$data['vendors'] = $this->Vendor_model->get_all_vendors();
	//         $this->load->view('layout/header', $data);
	//         $this->load->view('layout/menubar');
	// 		$this->load->view('materialList', $data);
	// 		$this->load->view('layout/footer');
	// 	}
	// 	else
	// 	{
	// 		redirect('welcome');
	// 	}
	// }

	//this is callback () to check unique combination of material & vendor
	public function unique_check()
    {
		$mat_name = $this->input->post('mat_name');
		$vendorName = $this->input->post('vendorName');
		$this->db->select('id');
		$this->db->from('materials');
		$this->db->where('vendor', $vendorName);
		$this->db->where('material_name', $mat_name);
		$qry  = $this->db->get();
		$num = $qry->num_rows();
		if($num > 0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	 public function mode_validate($value)
    {
		/*if($value == 'Cheque')
		{
			$this->form_validation->set_rules('cheque_no', 'Cheque No', 'trim|required');
			$this->form_validation->set_message('mode_validate', "Please enter cheque No");
			return false;
		}
		elseif($value == 'IMPS')
		{
			$this->form_validation->set_rules('trn_no', 'Transaction No', 'trim|required');
			$this->form_validation->set_message('mode_validate', "Please enter Transaction No");
			return false;
		}*/
		if ($this->input->post('bill_amount')=='' && $this->input->post('paid')=='')
		{
			$this->form_validation->set_message('mode_validate', "Please enter Bill Amount/Paid Amount");
			return false;
		}
		else
		{
			return true;
		}
	}
	// Add new MATERIAL form
	public function add_new()
	{

		if( $this->input->post('add_material') != NULL )
		{
			$this->form_validation->set_rules('vendorName', 'Vendor Name', 'trim|required');
     		$this->form_validation->set_rules('bill_amount', 'Bill Amount', 'trim');
     		$this->form_validation->set_rules('paid', 'Paid Amount', 'trim');
     		$this->form_validation->set_rules('mode', 'Payment Mode', 'required|callback_mode_validate');
     		$this->form_validation->set_rules('cheque_no', 'Cheque No', 'trim');
     		$this->form_validation->set_rules('trn_no', 'Transaction No', 'trim');

			if ($this->form_validation->run() == false)
			{
				$data['title'] = ucwords('Add new Material');
	        	$data['username'] = $this->session->userdata('logged_in');
	        	$data['vendors'] = $this->Material_model->get_all_vendors();

		        $this->load->view('layout/header', $data);
		        $this->load->view('layout/menubar');
				$this->load->view('material_add', $data);
				$this->load->view('layout/footer');
			}
			else
			{
				$postData = $this->input->post();
				if(count($postData['mat_name']) > 0)
				{
					$mat_name 	 = 	implode(',', $postData['mat_name']);
					$hsn		 = 	implode(',', $postData['hsn']);
					$batch		 =	implode(',', $postData['batch']);
					$qnty 		 =	implode(',', $postData['qnty'])	;
					$rate 		 =	implode(',', $postData['rate']);
				}
				else
				{
					$mat_name = $hsn = $batch = $qnty = $rate = NULL;
				}
				$invoice 		=	 $postData['invoice'] ? $postData['invoice'] : NULL;
				$challan 		=	 $postData['challan'] ? $postData['challan'] : NULL;
				$vendorName		=	 $postData['vendorName'];
				$last_bal		=	 $postData['last_bal'];
				$bill_amount	=	 $postData['bill_amount'];
				$paid 			=	 $postData['paid'];
				$update_new_bal =	 $postData['new_bal'];
				$mode 			=	 $postData['mode'];
				$cheque_no 		=	 $postData['cheque_no'];
				$trn_no 		=	 $postData['trn_no'];

				$add_data = array(
					'material_name' => strtoupper(trim($mat_name)),
					'hsn' => strtoupper($hsn),
					'batch_no' => strtoupper($batch),
					'quantity' => $qnty,
					'rate' => $rate,
					'invoice' => $invoice,
					'challan' => $challan,
					'vendor' => $vendorName,
					'last_amount'	=> $last_bal,
					'bill_amount' => $bill_amount,
					'paid_amount' => $paid,
					'new_amount'  => $update_new_bal,
					'pay_mode'     => $mode,
					'transaction_no' => $trn_no,
					'cheque_no'     => $cheque_no,
				);

				$data_update = array('debit_balance' => $update_new_bal);

				$insert = $this->Material_model->add_material($add_data);
				$update = $this->Vendor_model->update_vendor($data_update, $vendorName);
				if($insert > 0)
				{
					$this->session->set_flashdata('success', 'Material payment added successfully.');
					redirect('Material');
				}
				else
				{
					$this->session->set_flashdata('failed', 'Some problem occurred, please try again.');
					$this->load->view('layout/header', $data);
			        $this->load->view('layout/menubar');
					$this->load->view('material_add', $data);
					$this->load->view('layout/footer');
				}
			}
     	}

		elseif($this->session->userdata('logged_in'))
        {
        	$data['title'] = ucwords('Add new Material');
        	$data['username'] = $this->session->userdata('logged_in');
        	$data['vendors'] = $this->Material_model->get_all_vendors();

	        $this->load->view('layout/header', $data);
	        $this->load->view('layout/menubar');
			$this->load->view('material_add', $data);
			$this->load->view('layout/footer');
		}
		else
		{
			redirect('Welcome');
		}
	}

	//form to UPDATE Material
	public function edit()
	{
		$mat_id = $this->input->post('mat_id');
		if( $mat_id)
		{
			$cust_data = $this->Material_model->get_material_byID($mat_id);
			if($cust_data)
			{
				$resp['result'] =	 $cust_data;
				$resp['status'] =	'passed';
			}
			else
			{
				$resp['result'] =	 'Sorry details not found';
				$resp['status'] =	'failed';
			}
		}
		echo json_encode($resp);
	}
	public function download_pdf()
	{
		$cust_name = $this->input->post('customerName');
		$frm_mth = $this->input->post('frm_mth');
		$frm_yr = $this->input->post('frm_yr');
		$to_mth = $this->input->post('to_mth');
		$to_yr = $this->input->post('to_yr');

		$this->form_validation->set_rules('customerName', 'Vendor Name', 'trim|required');
		$this->form_validation->set_rules('frm_mth', 'From Month', 'trim|required');
		$this->form_validation->set_rules('frm_yr', 'From Year', 'trim|required');

		if(!$this->session->userdata('logged_in'))
		{
			redirect('Welcome');
		}

		if ($this->form_validation->run() == FALSE)
        {
			$response['result'] = 'Please select Vendor, month and year.';
        	$response['status']   = 'failed';
        }

		if( $cust_name && $frm_mth &&  $frm_yr)
		{
        	$db_data = $this->Material_model->vendor_ledger_byDate($cust_name,$frm_mth,$frm_yr,$to_mth,$to_yr)->result_array();
			$ledger_row = array();

			if( count($db_data) > 0)
			{
				//$data['db_data'] = $db_data;
				$filename = $db_data[0]['vendor_name'].'-ledger.xls';
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename=\"$filename\"");
				$isPrintHeader = false;
				$excelTable = '';
          		$excelTable .= '<table border="1"><tr><td colspan="8"><h3 style="text-align: center"><b>'.$db_data[0]['vendor_name'].'</h3></td></tr>
                  <tr><td colspan="8"><h4 style="text-align: center"><b>'.
                  $db_data[0]['address'].','.
                  $db_data[0]['area'].','.
                  $db_data[0]['city'].'</h4></td></tr>
                  <tr><td colspan="8">&nbsp;<br /></td></tr>';

          		$excelTable .= '<tr><th>LAST AMOUNT</th> <th>BILL AMOUNT</th> <th>PAID AMOUNT</th>
           			<th>NEW AMOUNT</th> <th>PAY MODE</th> <th>TRN No.</th> <th>CHEQUE No</th>
           			<th>DATE</th></tr>';

		        foreach ($db_data as $data_row)
		        {
		        	$data_row['buy_date'] = date('d F, Y', strtotime($data_row['buy_date']) );
		        	$excelTable.= '<tr><td>'.
		            $data_row['last_amount'].'</td><td>'.$data_row['bill_amount'].'</td><td>'.
		            $data_row['paid_amount'].'</td><td>'.$data_row['new_amount'].'</td><td>'.
		            $data_row['pay_mode'].'</td><td>'.$data_row['transaction_no'].'</td><td>'.
		            $data_row['cheque_no'].'</td><td>'.$data_row['buy_date'].'</td></tr>';
		        }

				$response['result']	=  "data:application/vnd.ms-excel;base64,".base64_encode($excelTable);
				$response['status'] = 'passed';
				$response['filename'] = $filename;
				//echo json_encode($response);
			}
			else
			{
				$response['result'] = 'Sorry! no record found';
        		$response['status'] = 'failed';
			}
		}
		echo json_encode($response);
	}
	// Logout from admin page
	public function logout()
	{
		$this->session->unset_userdata('logged_in');
		header("location:". site_url('?status=loggedout'));
	}

	// public function deleteMaterial()
	// {
	// 	if($this->input->post('row_id'))
	// 	{
	// 		$id = $this->input->post('row_id');
	// 		$upd = $this->Material_model->delete_by_id($id);
	// 		if($upd > 0)
	// 		{
	// 			$resp['status'] = 'passed';
	// 			$resp['result'] = 'Material deleted successfully.';
	// 		}
	// 		else
	// 		{
	// 			$resp['status'] = 'failed';
	// 			$resp['result'] = 'Some problem occurred, please try again';
	// 		}
	// 		echo json_encode($resp);
	// 	}
	// }

}
