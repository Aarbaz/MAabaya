<?php
defined('BASEPATH') or exit('No direct script access allowed');

class History extends CI_Controller
{
	/*
		1. index() displays a list of all customers
		2. add_new() insert new customer in DB
		3. call edit() to modify a customer
		*/

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('Customer_model');
		$this->load->model('Purchaser_model');
		$this->load->model('Making_model');
		$this->load->model('History_model');
		$this->load->model('Balance_model');
		$this->load->library("tcpdf");
		$this->load->helper('file');
	}

	public function index()
	{
		if ($this->session->userdata('logged_in')) {
			$data['title'] = 'Balance list';
			$data['username'] = $this->session->userdata('logged_in');
			$data['ledger_list'] = $this->History_model->get_customer_ledger();
			$data['history'] = $this->History_model->get_all_history();
			$data['custList'] = $this->Customer_model->get_customers();
			$this->load->view('layout/header', $data);
			$this->load->view('layout/menubar');
			$this->load->view('historyList', $data);
			$this->load->view('layout/footer');
		} else {
			redirect('Welcome');
		}
	}

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

		if (!$this->session->userdata('logged_in')) {
			redirect('Welcome');
		}

		if ($this->form_validation->run() == FALSE) {
			$response['result'] = 'Please select customer, month and year.';
			$response['status'] = 'failed';
			//echo json_encode($response);
		}
		if ($cust_name && $frm_mth && $frm_yr) {
			$db_data = $this->Balance_model->customer_ledger_byDate($cust_name, $frm_mth, $frm_yr, $to_mth, $to_yr)->result_array();

			// get user name
			$this->db->where('id', $cust_name);
			$query = $this->db->get('customers');
			$row = $query->row();
			$username = $row->name;

			$data_pdf = $db_data; // Replace $dynamic_array with your actual dynamic array
			$filename = $frm_mth . '_' . $to_mth . '.pdf';
			$pdf_file = APPPATH . 'balance_sheet/' . $username . '/' . $filename;
			$file = $filename;

			if (file_exists($pdf_file)) {

				// Set appropriate headers for download
				header('Content-Type: application/pdf');
				header("Content-Disposition: inline; filename=\"$file\"");
				readfile($pdf_file);
			} else {

				$this->load->library('tcpdf/tcpdf.php');

				$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				$pdf->setPrintHeader(false);
				$pdf->setPrintFooter(false);
				$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT, true);
				//$pdf->SetFont('helvetica', '', 10);
				$pdf->SetFont('times', '', 10);

				$pdf_data = $this->load->view('balance_pdf', array('data_pdf' => $data_pdf), true);
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

				$response['result'] = 'PDF generated successfully.';
				$response['status'] = 'passed';
				$this->session->set_flashdata('success', 'PDF generated successfully....');

				sleep(5);

				$this->download_pdf();
			}


			// ob_end_flush();
			// echo json_encode($pdfData);
		} else {
			$response['result'] = 'Sorry! no record found';
			$response['status'] = 'failed';
			redirect('History/download_pdf');
		}
		// echo json_encode($response);
	}



}