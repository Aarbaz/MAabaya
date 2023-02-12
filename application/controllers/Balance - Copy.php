<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Balance extends CI_Controller {

	/*
	methods name with call sequence
	1- index() contains a form to display all Invoice/Challan created for a customer
	2- showBalance() is called via ajax to fetch paid/balance for a customer and this is shown inside the table of div#form_data_box 
	3- saveBalance() updates paid/balance into challan/invoice table and insert in balance table
	4- balanceList is called to show detailed breakup of a bill payment history
	
	5- ledger() display a form to add ledger balance record customer vise
	6- ledgerBalance() get customer previous ledger balane sheet
	7 - savecustomerLedger() saves new customer ledger into DB
	*/

	public function __construct() 
    {
        parent::__construct();     
        $this->load->library('form_validation');
        $this->load->model('Balance_model');       
    }

	public function ledgerBalance()
	{
		if($this->session->userdata('logged_in'))
        {	
        	$cust_id = $this->input->post('cust_id');
        	$db_data = $this->Balance_model->get_ledger_balance($cust_id)->result();			

			$ledger_row = array();
			if( count($db_data) > 0)
			{
				foreach ($db_data as $data_row) 
				{
					$data_row->updated_date = date('Y-m-d', strtotime($data_row->updated_date) );
					$ledger_row['data'][] = $data_row;				
				}					
				$ledger_row['response'] = 'passed';											
			}
			else
			{
				$ledger_row['response'] = 'failed';				
			}
			echo json_encode($ledger_row);        	
		}
		else
		{
			redirect('Welcome');
		}
	}
	public function saveCustomerLedger()
	{
		if($this->session->userdata('logged_in'))
    	{	
	    	$post_data = $this->input->post();
	    	$customerName = $post_data['customerName'];
			$net_balance  = $post_data['net_balance'];

			$items = array(
				'ledger_date'=> $post_data['ledger_date'], 
				'product' => $post_data['items'], 
				'qnty' => $post_data['qnty'], 
				'rate' => $post_data['rate'], 
				'amount' => $post_data['amount'], 
				'paid' => $post_data['paid'], 
				'balance' => $post_data['balance'],				
			);              
	  		  
			$len = count($items['product']);  
	  		  
	  		$insert_data = array();                    
			for ($i=0; $i < $len; $i++)
			{ 				
				if( $items['product'][$i] )
				{
					$insertArray = array();
					$insertArray['customer_id']    = $customerName;
					$insertArray['product']    = $items['product'][$i];
					$insertArray['quantity']    = $items['qnty'][$i];
					$insertArray['rate']    = $items['rate'][$i];		
					$insertArray['total']    = $items['amount'][$i];
					$insertArray['paid']    = $items['paid'][$i] ?: '0.00';
					$insertArray['balance']    = $items['balance'][$i] ?: $items['amount'][$i];
					$insertArray['updated_date']    = $items['ledger_date'][$i] ?: date('Y-m-d');
					$insert_data[] = $insertArray;	
				}												
			} 

			/*		echo '<pre>';
			print_r($insert_data);
			exit;*/
			$update_data = array('last_amount' => $net_balance);
			$cust_id = $customerName;
			$update = $this->Balance_model->update_customer($update_data, $cust_id); 
   
			$insert_data_len = count($insert_data);		

			$ins = '';	
			//insert new balance data in balance table
			if($insert_data_len > 0)
			{			
				$ins = $this->Balance_model->ledger_balance($insert_data); 
			}
			
			if($ins)
			{
				$result['message'] = "Records updated Successfully." ;
				$result['cust_id'] = $cust_id;            		
				$result['updated'] = "yes" ;            		
			}
			else
			{
				$result['message'] = "Nothing updated." ;
				$result['updated'] = "no" ;
			}

			echo json_encode($result); 	
		}
		else
		{
			redirect('Welcome');
		}
	}

	public function ledger()
	{
		if($this->session->userdata('logged_in'))
        {		
        	$data['title'] = 'Create Ledger balance';
        	$data['username'] = $this->session->userdata('logged_in');			
			$data['custList'] = $this->Balance_model->get_all_customer();
        	$data['productList'] = $this->Balance_model->get_all_products();
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
		
	public function balanceList()
	{
		if($this->session->userdata('logged_in'))
        {		
        	$data['title'] = 'Balance List';
        	$data['username'] = $this->session->userdata('logged_in');		
        	
        	$billno =  $this->input->post('billno');
        	$bill_type =  $this->input->post('bill_type');
			$db_data = $this->Balance_model->get_balance($billno)->result();			

			$chalan_invoice_row = array();
			if( count($db_data) > 0)
			{
				foreach ($db_data as $data_row) 
				{
					$chalan_invoice_row['data'][] = $data_row;					
				}					
				$chalan_invoice_row['response'] = 'passed';				
				$chalan_invoice_row['type'] = $bill_type;				
			}
			else
			{
				$chalan_invoice_row['response'] = 'failed';				
			}
			echo json_encode($chalan_invoice_row);
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
    
	// Fetch Challan OR Invoice records for a customer maybe using date range
	public function showBalance()
	{
		$data = $_POST;
		$bal_type = $data['bal_type'];
		$customerName = $data['customerName'];
		$from_date = $data['frm_date'];
		$to_date = $data['to_date'] ? $data['to_date'] : date('Y-m-d');

		if($from_date)
		{
			$from_date = date('Y-m-d 00:00:00', strtotime($from_date));
		}
		if($to_date)
		{
			$to_date = date('Y-m-d 23:59:59', strtotime($to_date));
		}		
		
		$chalan_invoice_row = array();
	
		if($bal_type =='challan')
		{
			if($from_date && $to_date)
			{				
				$db_data = $this->Balance_model->get_challan_list($customerName, $from_date, $to_date)->result();
			}
			else
			{				
				$db_data = $this->Balance_model->get_challan_list($customerName)->result();
			}					
		}

		elseif($bal_type =='invoice')
		{
			if($from_date && $to_date)
			{
				$db_data = $this->Balance_model->get_invoice_list($customerName, $from_date, $to_date)->result();
			}
			else
			{
				$db_data = $this->Balance_model->get_invoice_list($customerName)->result();
			}
		}
		
		if( count($db_data) > 0)
		{
			foreach ($db_data as $data_row) 
			{
				$chalan_invoice_row['data'][] = $data_row;
			}
						
			$chalan_invoice_row['type'] = $bal_type;
			$chalan_invoice_row['response'] = 'passed';
		}
		else
		{
			$chalan_invoice_row['response'] = 'failed';
			$chalan_invoice_row['type'] = $bal_type;			
		}
		echo json_encode($chalan_invoice_row);
	}	

	//update paid/balance field and insert new records into balance table
	public function saveBalance()
	{
		$post_data = $this->input->post();				
		
  		$items = array(
  			'chk_update' => $post_data['chk_update'], 
  			'row_id'     => $post_data['row_id'], 
  			'bill_no'    => $post_data['bill_no'], 
  			'total'      => $post_data['total'], 
  			'paid'       => $post_data['paid'], 
  			'cust_id'    => $post_data['cust_id'], 
  			'bil_type'   => $post_data['bil_type'], 
  			'balance'    => $post_data['bal'],
  			'bal_hide'   => $post_data['bal_hide'],
  			'paid_hide'  => $post_data['paid_hide'],
  		);              


  		$len = count($items['row_id']);   
  		$update_data = array();                    
  		$insert_data = array();                    
		for ($i=0; $i < $len; $i++)
		{ 
			$updateArray = array();
			$insertArray = array();
			if(!empty($items['chk_update'][$i]))
			{
				$updateArray['sr_no'] = $items['row_id'][$i];
				$updateArray['paid'] = $items['paid'][$i];
				$updateArray['balance'] = $items['balance'][$i];
				$update_data[] = $updateArray;
			
				$insertArray['customer_id']    = $items['cust_id'][$i];
				$insertArray['bill_type']    = $items['bil_type'][$i];
				$insertArray['bill_no']    = $items['bill_no'][$i];
				$insertArray['total_bill']    = $items['total'][$i];		
				$insertArray['paid_bill']    = $items['paid'][$i];
				$insertArray['balance_bill']    = $items['balance'][$i];
				$insert_data[] = $insertArray;	
			}	
		}             
    
		/*      	echo '<pre>';
  		print_r($update_data);
  		print_r($insert_data);
  		exit;*/
		$update_data_len = count($update_data);
		$insert_data_len = count($insert_data);

		$update_type =  $post_data['bil_type'][0];

		//update paid and balance in challan table and insider_bill table
		$upd = ''; $ins = '';
		if($update_data_len > 0)
		{
			if($update_type == 'challan')
			{
				$upd = $this->Balance_model->update_challan($update_data); 
			}
			else
			{
				$upd = $this->Balance_model->update_invoice($update_data); 
			}
		}	
		//insert new balance data in balance table
		if($insert_data_len > 0)
		{			
			$ins = $this->Balance_model->insert_balance($insert_data); 
		}
		
		if($upd || $ins)
		{
			$result['message'] = "Records updated Successfully." ;
			$result['updated'] = "yes" ;            		
		}
		else
		{
			$result['message'] = "Nothing updated." ;
			$result['updated'] = "no" ;
		}

		echo json_encode($result); 
	} 	

	//Download pdf challan
	public function download_pdf()
	{
		$cust_name = $this->input->post('cust_name_excel');
		$cust_id = $this->input->post('cust_id_excel');

		if(!$this->session->userdata('logged_in'))
		{
			redirect('Welcome');
		}

		elseif( $cust_name && $cust_id )
		{        	
        	$db_data = $this->Balance_model->get_ledger_balance($cust_id)->result_array();			
			$ledger_row = array();

			if( count($db_data) > 0)
			{
				$filename = $cust_name.'-'.$cust_id.'-ledger.xls';
			    header("Content-Type: application/vnd.ms-excel");
			    header("Content-Disposition: attachment; filename=\"$filename\"");
			    $isPrintHeader = false;		
			    $excelTable = '';
			    $excelTable .= '<table border="1"><tr><td colspan="7"><h3 style="text-align: center"><b>'.$cust_name.'</h3></td></tr>';
			    $excelTable .= '<tr><th>PRODUCT</th> <th>QUANTITY</th> <th>RATE</th> <th>TOTAL</th> <th>PAID</th>
			    				<th>BALANCE</th> <th>DATE</th></tr>';

				foreach ($db_data as $data_row) 
				{
					$data_row['updated_date'] = date('Y-m-d', strtotime($data_row['updated_date']) );
					unset($data_row['id']);
					unset($data_row['customer_id']);

					$excelTable.= '<tr><td>'.$data_row['product'].'</td><td>'.$data_row['quantity'].'</td><td>'.$data_row['rate'].'</td><td>'.$data_row['total'].'</td><td>'.$data_row['paid'].'</td><td>'.$data_row['balance'].
					'</td><td>'.$data_row['updated_date'].'</td></tr>';			
				}		
				echo $excelTable;					
			}
			else
			{	
				echo '<script>alert("Sorry no record found");
					window.location.href="'.site_url('Balance/ledger').'";</script>';
				//redirect('Balance/ledger');
			}					
		}
	}

	// Logout from admin page
	public function logout()
	{
		$this->session->unset_userdata('logged_in');
		header("location:". site_url('?status=loggedout'));
	}

}
