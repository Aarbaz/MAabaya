<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Balance extends CI_Controller {

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
        $this->load->model('Balance_model');
        $this->load->model('Customer_model');
    }

	public function ledgerBalance()
	{
		if($this->session->userdata('logged_in'))
        {
        	$cust_id = $this->input->post('mat_id');
        	$bal = $this->Balance_model->get_customer_ledger($cust_id);
        	if($bal)
        	{
        		$ledger_row['status'] = 'passed';
        		$ledger_row['result']      = $bal;
        	}
			else
			{
				$ledger_row['status'] = 'failed';
			}
			echo json_encode($ledger_row);
		}
		else
		{
			redirect('Welcome');
		}
	}

	public function mode_validate($value)
    {
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
	public function ledger()
	{
		if( $this->input->post('add_material') != NULL )
		{
			$this->form_validation->set_rules('vendorName', 'Customer Name', 'trim|required');
     		$this->form_validation->set_rules('bill_amount', 'Bill Amount', 'trim');
     		$this->form_validation->set_rules('paid', 'Paid Amount', 'trim');
     		$this->form_validation->set_rules('mode', 'Payment Mode', 'required|callback_mode_validate');
     		$this->form_validation->set_rules('cheque_no', 'Cheque No', 'trim');
     		$this->form_validation->set_rules('trn_no', 'Transaction No', 'trim');

			if ($this->form_validation->run() == false)
			{
				$data['title'] = ucwords('Create Ledger balance');
	        	$data['username'] = $this->session->userdata('logged_in');
	        	$data['custList'] = $this->Balance_model->get_all_customer();
        		// $data['productList'] = $this->Balance_model->get_all_products();
		        $this->load->view('layout/header', $data);
		        $this->load->view('layout/menubar');
				$this->load->view('balance_ledger', $data);
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
					'product_name' => strtoupper(trim($mat_name)),
					'hsn' => strtoupper($hsn),
					'batch_no' => strtoupper($batch),
					'quantity' => $qnty,
					'rate' => $rate,
					'invoice' => $invoice,
					'challan' => $challan,
					'customer' => $vendorName,
					'last_amount'	=> $last_bal,
					'bill_amount' => $bill_amount,
					'paid_amount' => $paid,
					'new_amount'  => $update_new_bal,
					'payment_mode'     => $mode,
					'transaction_no' => $trn_no,
					'cheque_no'     => $cheque_no,
				);

				$data_update = array('last_amount' => $update_new_bal);

				$insert = $this->Balance_model->add_customer_ledger($add_data);
				$update = $this->Balance_model->update_customer($data_update, $vendorName);
				if($insert > 0)
				{
					$this->session->set_flashdata('success', 'Balance added successfully.');
					redirect('Balance');
				}
				else
				{
					$this->session->set_flashdata('failed', 'Some problem occurred, please try again.');
		        	$data['title'] = 'Create Ledger balance';
		        	$data['username'] = $this->session->userdata('logged_in');
					$data['custList'] = $this->Balance_model->get_all_customer();
		        	// $data['productList'] = $this->Balance_model->get_all_products();
			        $this->load->view('layout/header', $data);
			        $this->load->view('layout/menubar');
					$this->load->view('balance_ledger', $data);
					$this->load->view('layout/footer');
				}
			}
     	}
		if($this->session->userdata('logged_in'))
        {
        	$data['title'] = 'Create Ledger balance';
        	$data['username'] = $this->session->userdata('logged_in');
			$data['custList'] = $this->Balance_model->get_all_customer();
        	// $data['productList'] = $this->Balance_model->get_all_products();
	        $this->load->view('layout/header', $data);
	        $this->load->view('layout/menubar');
			$this->load->view('balance_ledger', $data);
			$this->load->view('layout/footer');
		}
		else
		{
			redirect('Welcome');
		}
	}

	public function index()
	{
		if($this->session->userdata('logged_in'))
        {
        	$data['title'] = 'Balance list';
        	$data['username'] = $this->session->userdata('logged_in');
        	$data['ledger_list'] = $this->Balance_model->get_customer_ledger();
        	$data['custList'] = $this->Customer_model->get_customers();
	        $this->load->view('layout/header', $data);
	        $this->load->view('layout/menubar');
			$this->load->view('balanceList', $data);
			$this->load->view('layout/footer');
		}
		else
		{
			redirect('Welcome');
		}
	}

	//Download pdf challan
	public function download_pdf()
	{
		$cust_name = $this->input->post('customerName');
		$frm_mth = $this->input->post('frm_mth');
		$frm_yr = $this->input->post('frm_yr');
		$to_mth = $this->input->post('to_mth');
		$to_yr = $this->input->post('to_yr');

		$this->form_validation->set_rules('customerName', 'Customer Name', 'trim|required');
		$this->form_validation->set_rules('frm_mth', 'From Month', 'trim|required');
		$this->form_validation->set_rules('frm_yr', 'From Year', 'trim|required');

		if(!$this->session->userdata('logged_in'))
		{
			redirect('Welcome');
		}

		if ($this->form_validation->run() == FALSE)
        {
			$response['result'] = 'Please select customer, month and year.';
        	$response['status']   = 'failed';
        	//echo json_encode($response);
        }

		if( $cust_name && $frm_mth &&  $frm_yr)
		{
        	$db_data = $this->Balance_model->customer_ledger_byDate($cust_name,$frm_mth,$frm_yr,$to_mth,$to_yr)->result_array();
			$ledger_row = array();

			if( count($db_data) > 0)
			{
				//$data['db_data'] = $db_data;
				$filename = $db_data[0]['name'].'-ledger.xls';
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename=\"$filename\"");
				$isPrintHeader = false;
				$excelTable = '';
          		$excelTable .= '<table border="1"><tr><td colspan="8"><h3 style="text-align: center"><b>'.$db_data[0]['name'].'</h3></td></tr>
                  <tr><td colspan="8"><h4 style="text-align: center"><b>'.
                  $db_data[0]['address'].'</h4></td></tr>
                  <tr><td colspan="8">&nbsp;<br /></td></tr>';

          		$excelTable .= '<tr><th>LAST AMOUNT</th> <th>BILL AMOUNT</th> <th>PAID AMOUNT</th>
           			<th>NEW AMOUNT</th> <th>PAY MODE</th> <th>TRN No.</th> <th>CHEQUE No</th>
           			<th>DATE</th></tr>';

		        foreach ($db_data as $data_row)
		        {
		        	$data_row['dated'] = date('d F, Y', strtotime($data_row['dated']) );
		        	$excelTable.= '<tr><td>'.
		            $data_row['last_amount'].'</td><td>'.$data_row['bill_amount'].'</td><td>'.
		            $data_row['paid_amount'].'</td><td>'.$data_row['new_amount'].'</td><td>'.
		            $data_row['payment_mode'].'</td><td>'.$data_row['transaction_no'].'</td><td>'.
		            $data_row['cheque_no'].'</td><td>'.$data_row['dated'].'</td></tr>';
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

	public function download_ledger($data = array())
	{
		$this->load->view('balance_ledger_customer',$data);
	}
	// Logout from admin page
	public function logout()
	{
		$this->session->unset_userdata('logged_in');
		header("location:". site_url('?status=loggedout'));
	}

}
