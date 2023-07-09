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
				$this->load->library("tcpdf");

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
						$data['balList'] = $this->Balance_model->get_last_balance();

        		// $data['productList'] = $this->Balance_model->get_all_products();
		        $this->load->view('layout/header', $data);
		        $this->load->view('layout/menubar');
				$this->load->view('balance_ledger', $data);
				$this->load->view('layout/footer');
			}
			else
			{



				$postData = $this->input->post();
				// if(count($postData['mat_name']) > 0)
				// {
				// 	$mat_name 	 = 	implode(',', $postData['mat_name']);
				// 	$hsn		 = 	implode(',', $postData['hsn']);
				// 	$batch		 =	implode(',', $postData['batch']);
				// 	$qnty 		 =	implode(',', $postData['qnty'])	;
				// 	$rate 		 =	implode(',', $postData['rate']);
				// }
				// else
				// {
				// 	$mat_name = $hsn = $batch = $qnty = $rate = NULL;
				// }

				// $invoice 		=	 $postData['invoice'] ? $postData['invoice'] : NULL;
				// $challan 		=	 $postData['challan'] ? $postData['challan'] : NULL;
				$vendorName		=	 $postData['vendorName'];
				$last_bal		=	 $postData['last_bal'];
				// $bill_amount	=	 $postData['bill_amount'];
				$paid 			=	 $postData['paid'];
				$update_new_bal =	 $postData['new_bal'];
				$invoice =	 $postData['invoice_hidden'];
				$mode 			=	 $postData['mode'];
				$cheque_no 		=	 $postData['cheque_no'];
				$trn_no 		=	 $postData['trn_no'];


				$add_data = array(
					'total_bill'	=> $last_bal,
					'paid_bill' => $paid,
					'balance_bill'  => $update_new_bal,
					'updated_on' => date('Y-m-d H:i:s')
				);



				$add_ledgerdata = array(
					// 'product_name' => strtoupper(trim($mat_name)),
					// 'hsn' => strtoupper($hsn),
					// 'batch_no' => strtoupper($batch),
					// 'quantity' => $qnty,
					// 'rate' => $rate,
					'invoice' => $postData['invoice_hidden'],
					// 'challan' => $challan,
					'customer' => $vendorName,
					// 'total_bill'	=> $last_bal,
					'bill_amount' => $last_bal,
					'paid_amount' => $paid,
					'last_amount'  => $update_new_bal,
					'payment_mode'     => $mode,
					'transaction_no' => $trn_no,
					'cheque_no'     => $cheque_no,
					// 'updated_on' => date('Y-m-d H:i:s')
				);

				$data_update = array('last_amount' => $update_new_bal);

				$insert = $this->Balance_model->update_balance($add_data,$vendorName);
				$insert = $this->Balance_model->add_customer_ledger($add_ledgerdata);
				// $update = $this->Balance_model->update_customer($data_update, $vendorName);

				$customer_id= $vendorName;
	      $this->db->select('*');
	      $this->db->from('customers');
	      $this->db->where('id',$customer_id);
	      $query = $this->db->get();
	      $purchaser_name = $query->row();
	      $cust_name = $purchaser_name->name;

				$data_pdf = [
					'invoice' => $invoice,
					// 'challan' => $challan,
					'customer' => $cust_name,
					// 'total_bill'	=> $last_bal,
					'bill_amount' => $last_bal,
					'paid_amount' => $paid,
					'last_amount'  => $update_new_bal,
					'payment_mode'     => $mode,
					'transaction_no' => $trn_no,
					'cheque_no'     => $cheque_no,
		];

				// print_r($data_pdf);
				// die();
				$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				$pdf->setPrintHeader(false);
				$pdf->setPrintFooter(false);
				$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT, true);
				//$pdf->SetFont('helvetica', '', 10);
				$pdf->SetFont("", "", 10);
				$pdf_data = $this->load->view("balance_bill", $data_pdf, true);
				$pdf->addPage();
				$pdf->writeHTML($pdf_data, true, false, true, false, "");
				$filename = strtoupper($invoice).".pdf";
				$dir = APPPATH . "/Balance Amount/" . $cust_name . "/";
				if (!is_dir($dir)) {
						mkdir($dir, 0777, true);
				}
				$save_path = $dir . $filename;
				ob_end_clean();
				// $pdf->Output($save_path, "I");
				$pdf->Output($save_path, "F");

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
					$data['balList'] = $this->Balance_model->get_last_balance();


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
			$data['balList'] = $this->Balance_model->get_last_balance();

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
        	// $data['ledger_list'] = $this->Balance_model->get_customer_ledger();
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
				$filename = $db_data[0]['name'];



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

	public function billBycust()
    {
      $id = $this->input->post('vendorName');
      $data = $this->Balance_model->get_billcust($id);
      echo json_encode($data);
    }
	// public function amountByBill()
    // {
    //   $id = $this->input->post('invoice');
    //   $data = $this->Balance_model->get_billinvoice($id);
    //   echo json_encode($data);
    // }

	// Logout from admin page
	public function logout()
	{
		$this->session->unset_userdata('logged_in');
		header("location:". site_url('?status=loggedout'));
	}

}
