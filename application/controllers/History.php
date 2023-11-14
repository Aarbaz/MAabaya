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
		$this->load->model('Design_model');
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
			$data['MaterialHistory'] = $this->History_model->get_all_material_history();
			$data['picesHistoryList'] = $this->History_model->get_all_pices_history();
			$data['custList'] = $this->Customer_model->get_customers();
			$data['materialList'] = $this->Purchaser_model->get_all_material();
			$data['designs'] = $this->Design_model->get_all_design();
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


	public function downloadSinglePdf($filePath, $cust_name, $invoice_id)
	{

		if (!$this->session->userdata('logged_in')) {
			redirect('Welcome');
		} elseif ($cust_name && $invoice_id) {
			$pdf_file = APPPATH . $filePath.'/' . rawurldecode($cust_name) . '/' . $invoice_id . '.pdf';
			$file = $invoice_id . '.pdf';

			if (file_exists($pdf_file)) {
				header("Content-Type: application/pdf");
				header("Content-Disposition: attachment;filename=\"$file\"");
				readfile($pdf_file);
			} else {
				$this->session->set_flashdata('error', 'Sorry! file not found...');
				redirect('History');
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

	public function get_history_by_material_id() {

		$material_id = $this->input->post('material_id');
		$from_date = $this->input->post('from_date');
		$to_date = $this->input->post('to_date');

		$this->form_validation->set_rules('material_id', 'Material Name', 'trim|required');
		$this->form_validation->set_rules('from_date', 'From Date', 'trim|required');
		$this->form_validation->set_rules('to_date', 'To Date', 'trim|required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('error', ' ');
			$this->index();
		}
		else {
			$db_data = $this->History_model->getHistoryByMaterialId($material_id, $from_date, $to_date);
			$this->db->where('id', $material_id);
			$query = $this->db->get('material');
			$row = $query->row();
			$material_name = $row->material_name;
			// Replace spaces with underscores and convert to lowercase
			$date_range = $from_date. ' To ' . $to_date;
			$encoded_material_name = str_replace(' ', '_', strtolower($material_name));
			$invoice_id = $encoded_material_name. '_' . $from_date . $to_date;
			$pdf_file = APPPATH . 'material_history/' . $encoded_material_name . '/' . $invoice_id . '.pdf';
			$file = $invoice_id . '.pdf';

			if (file_exists($pdf_file)) {

				header('Content-type: application/pdf');
				header('Content-Disposition: attachment; filename="' . $file . '"');
				header('Content-Transfer-Encoding: binary');
				header('Content-Length: ' . filesize($pdf_file));
				header('Accept-Ranges: bytes');
				readfile($pdf_file);

			} else {
				$data_pdf = $db_data; // Replace $dynamic_array with your actual dynamic array
				$filename = $encoded_material_name. '_' . $from_date . $to_date . '.pdf';
				$pdf_file = APPPATH . 'material_history/' . $encoded_material_name . '/' . $filename;
				$file = $filename;
				// print_r($data_pdf);
				// die();
				$this->load->library('tcpdf/tcpdf.php');

				$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				$pdf->setPrintHeader(false);
				$pdf->setPrintFooter(false);
				$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT, true);

				$pdf->SetFont('times', '', 10);

				$pdf_data = $this->load->view('material_history_pdf', array('data_pdf' => $data_pdf, 'date_range' => $date_range, 'material_name' => $material_name), true);
				$pdf->addPage();

				$pdf->writeHTML($pdf_data, true, false, true, false, '');

				$dir = APPPATH . '/material_history/' . $encoded_material_name . '/';

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
				$this->index();
			}
		}	
	}
	public function get_history_by_design_num() {

		$design_num = $this->input->post('design_num');
		$from_date = $this->input->post('from_date');
		$to_date = $this->input->post('to_date');

		$this->form_validation->set_rules('design_num', 'Material Name', 'trim|required');
		$this->form_validation->set_rules('from_date', 'From Date', 'trim|required');
		$this->form_validation->set_rules('to_date', 'To Date', 'trim|required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('error', ' ');
			$this->index();
		}
		else {
			$db_data = $this->History_model->getHistoryByDesignId($design_num, $from_date, $to_date);
			$this->db->where('id', $design_num);
			$query = $this->db->get('designs');
			$row = $query->row();
			$design_num = $row->design_num;
			// Replace spaces with underscores and convert to lowercase
			$date_range = $from_date. ' To ' . $to_date;
			$encoded_design_num = str_replace(' ', '_', strtolower($design_num));
			$invoice_id = $encoded_design_num. '_' . $from_date . $to_date;
			$pdf_file = APPPATH . 'design_history/' . $encoded_design_num . '/' . $invoice_id . '.pdf';
			$file = $invoice_id . '.pdf';

			if (file_exists($pdf_file)) {

				header('Content-type: application/pdf');
				header('Content-Disposition: attachment; filename="' . $file . '"');
				header('Content-Transfer-Encoding: binary');
				header('Content-Length: ' . filesize($pdf_file));
				header('Accept-Ranges: bytes');
				readfile($pdf_file);

			} else {
				$data_pdf = $db_data; // Replace $dynamic_array with your actual dynamic array
				// print_r($data_pdf);
				// die();
				$filename = $encoded_design_num. '_' . $from_date . $to_date . '.pdf';
				$pdf_file = APPPATH . 'design_history/' . $encoded_design_num . '/' . $filename;
				$file = $filename;
				$this->load->library('tcpdf/tcpdf.php');

				$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				$pdf->setPrintHeader(false);
				$pdf->setPrintFooter(false);
				$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT, true);

				$pdf->SetFont('times', '', 10);

				$pdf_data = $this->load->view('design_history_pdf', array('data_pdf' => $data_pdf, 'date_range' => $date_range, 'design_num' => $design_num), true);
				$pdf->addPage();

				$pdf->writeHTML($pdf_data, true, false, true, false, '');

				$dir = APPPATH . '/design_history/' . $encoded_design_num . '/';

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
				$this->index();
			}
		}	
		// echo json_encode($response);
	}
	public function get_history_by_user_id() {

		$user_id = $this->input->post('user_id');
		$from_date = $this->input->post('from_date');
		$to_date = $this->input->post('to_date');

		$this->form_validation->set_rules('user_id', 'Material Name', 'trim|required');
		$this->form_validation->set_rules('from_date', 'From Date', 'trim|required');
		$this->form_validation->set_rules('to_date', 'To Date', 'trim|required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('error', ' ');
			$this->index();
		}
		else {
			$db_data = $this->History_model->getHistoryByUserId($user_id, $from_date, $to_date);
			$this->db->where('id', $user_id);
			$query = $this->db->get('customers');
			$row = $query->row();
			$user_name = $row->name;
			// Replace spaces with underscores and convert to lowercase
			$date_range = $from_date. ' To ' . $to_date;
			$encoded_user_name = str_replace(' ', '_', strtolower($user_name));
			$invoice_id = $encoded_user_name. '_' . $from_date . $to_date;
			$pdf_file = APPPATH . 'design_history/' . $encoded_user_name . '/' . $invoice_id . '.pdf';
			$file = $invoice_id . '.pdf';

			if (file_exists($pdf_file)) {

				header('Content-type: application/pdf');
				header('Content-Disposition: attachment; filename="' . $file . '"');
				header('Content-Transfer-Encoding: binary');
				header('Content-Length: ' . filesize($pdf_file));
				header('Accept-Ranges: bytes');
				readfile($pdf_file);

			} else {
				$data_pdf = $db_data; // Replace $dynamic_array with your actual dynamic array
				$filename = $encoded_user_name. '_' . $from_date . $to_date . '.pdf';
				$pdf_file = APPPATH . 'design_history/' . $encoded_user_name . '/' . $filename;
				$file = $filename;
				$this->load->library('tcpdf/tcpdf.php');

				$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				$pdf->setPrintHeader(false);
				$pdf->setPrintFooter(false);
				$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT, true);

				$pdf->SetFont('times', '', 10);

				$pdf_data = $this->load->view('user_history_pdf', array('data_pdf' => $data_pdf, 'date_range' => $date_range, 'user_name' => $user_name), true);
				$pdf->addPage();

				$pdf->writeHTML($pdf_data, true, false, true, false, '');

				$dir = APPPATH . '/design_history/' . $encoded_user_name . '/';

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
				$this->index();
			}
		}	
		// echo json_encode($response);
	}
}