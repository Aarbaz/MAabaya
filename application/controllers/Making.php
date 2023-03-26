<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Making extends CI_Controller
{
    /*
	 1. idex() is called to dislay list of materials
	 2. add_new() creates a new entry into DB
	 3. edit() to update product
	 */

    public function __construct()
    {
        parent::__construct();
        $this->load->library("form_validation");
        $this->load->model("Making_model");
        $this->load->model("Purchaser_model");
        $this->load->library("tcpdf");
        $this->load->library("upload");
        //$this->load->helper('pdf_helper');
        $this->load->helper("url");
        $this->load->model("Customer_model");
    }

    public function regexValidate($str)
    {
        if (!preg_match('/^[a-zA-Z0-9\s\.]+$/', $str)) {
            $this->form_validation->set_message(
                "regexValidate",
                "The %s field must only contain letters and/or number"
            );
            return false;
        } else {
            return true;
        }
    }

    public function index()
    {
        if ($this->session->userdata("logged_in")) {
            $data["title"] = ucfirst("Making List Page");
            $data["username"] = $this->session->userdata("logged_in");
            $data["products"] = $this->Making_model->get_all_material();
            $data["matList"] = $this->Purchaser_model->get_all_material();

            $this->load->view("layout/header", $data);
            $this->load->view("layout/menubar");
            $this->load->view("makingList", $data);
            $this->load->view("layout/footer");
        } else {
            redirect("Welcome");
        }
    }

    // //form to add new product
    public function add_new()
    {
        // $this->form_validation->set_rules('prod_name', 'Product Name', 'trim|required|callback_regexValidate|is_unique[products.product_name]',  array('is_unique' => 'This %s already exists.'));
        // $this->form_validation->set_rules('material_name', 'Material Name', 'trim|required');
        $this->form_validation->set_rules(
            "master_name",
            "Master Name",
            "trim|required"
        );
        // $this->form_validation->set_rules('p_price', 'Product Amount', 'trim|required|numeric');
        // $this->form_validation->set_rules('stock_q', 'Product Amount', 'trim|required|numeric');
        // $this->form_validation->set_rules('p_design_number', 'Design Number', 'trim|required');
        // $this->form_validation->set_rules('purchaserID', 'Purchaser ID', 'trim|required');
        // $this->form_validation->set_rules('pcs', 'Pcs', 'trim');
        // $this->form_validation->set_rules('meter', 'Meter', 'trim');
        // $this->form_validation->set_rules('product_exp', 'Expiry Date', 'trim|required');
        // $this->form_validation->set_rules('price_total', 'Product Quantity', 'trim|required|numeric');
        $validation = [
            [
                "field" => "material_name[]",
                "label" => "Material",
                "rules" => "required",
                "errors" => ["required" => " Please select %s. "],
            ],
        ];

        $validation2 = [
            [
                "field" => "stock_q[]",
                "label" => "Stock",
                "rules" => "required",
                "errors" => ["required" => " Please select %s. "],
            ],
        ];

        if ($this->input->post("add_making") != null) {
            if ($this->form_validation->run() == false) {
                $data["title"] = ucwords("Add new Making Page");
                $data["username"] = $this->session->userdata("logged_in");
                // $data["makList"] = $this->Making_model->get_all_making();
                $data["makList"] = $this->Making_model->get_last_maker_insider();

                $data["matList"] = $this->Purchaser_model->get_all_material();
                $data["custList"] = $this->Customer_model->get_mowner();
                $data["PurchaserList"] = $this->Customer_model->get_powner();

                $this->load->view("layout/header", $data);
                $this->load->view("layout/menubar");
                $this->load->view("making_add", $data);
                $this->load->view("layout/footer");
            } else {
                // POST data
                $postData = $this->input->post();
                $material_id = implode(
                    ",",
                    $this->input->post("material_name[]")
                );
                $material_id = trim($material_id, ",");
                $stock_q = implode(",", $this->input->post("stock_q[]"));
                $stock_q = trim($stock_q, ",");


                // $master_id = $this->input->post("master_id");
                $master_name = $this->input->post("master_name");

                $data = [
                    "material_id" => $material_id,
                    // "purchaser_owner_id" => $master_id,
                    "making_owner_id" => $master_name,
                    "stock" => $stock_q,
                    "maker_no" => strtoupper($postData["maker_no"]),
                ];

                $insert = $this->Making_model->add_material($data);

                $product_id = $this->db->insert_id();


                $material_ids = $this->input->post("material_name[]");
                $stocks = $this->input->post("stock_q[]");
                $oldstock = $this->input->post("stock_in[]");




                $m = 0;
                foreach ($stocks as $row) {
                    $dataStk["quantity"] = $oldstock[$m] - $stocks[$m];
                    $this->Purchaser_model->update_pstock_qty($dataStk,$master_id,$material_ids[$m]);

                    $dataMak["maker_id"] = $product_id;
                    // $dataMak["purchaser_owner_id"] = $master_id;
                    $dataMak["making_owner_id"] = $master_name;
                    $dataMak["materials_id"] = $material_ids[$m];
                    $dataMak["quantity"] = $stocks[$m];


                    $this->db->where('making_owner_id', $master_name);
                    $this->db->where('materials_id',$material_ids[$m]);
                    $querys = $this->db->get('maker_stock');
                    $rows = $querys->row();

                    /* print_r($rows);
                    die(); */
                    if ($querys->num_rows()) {
                      // If the product exists, update the quantity value in the database
                      // print_r($rows->quantity);
                      // die();
                      $data3 = array(
                        'quantity' => $rows->quantity + $stocks[$m],
                        // 'price' => $price[$i]
                      );

                      $this->db->where('making_owner_id', $master_name);
                      $this->db->where('materials_id',$material_ids[$m]);
                      $this->db->update('maker_stock', $data3);
                      /* print_r($material_ids);
                      die(); */
                    } else {
                      // If the product does not exist, insert a new row into the database
                      // $this->db->insert('purchaser_stock', array('p_design_number' => $product_id, 'stock_qty' => $quantity));
                      $this->Making_model->add_making_qty($dataMak);

                    }


                    $m++;
                }


                $json_data = json_encode($data);
          			$json_data_array = array(
          					'entry_from' => '1',
          					'json_data' => $json_data,
          			);
          			$insert_json_data = $this->Purchaser_model->create_history($json_data_array);

                if ($insert > 0) {


                  $customer_id= $master_name;
                  $this->db->select('*');
                  $this->db->from('customers');
                  $this->db->where('id',$customer_id);
                  $query = $this->db->get();
                  $master_name = $query->row();

                  $material_ids = implode(",",$this->input->post("material_name[]"));
                  $material_values = trim($material_name, ",");
                  $material_ids_values = explode(",", $material_ids);
                  $material_values = $material_ids_values;
                  $this->db->select('*');
                  $this->db->from('material');
                  $this->db->where_in('id', $material_values);
                  $query = $this->db->get();
                  $results = $query->result();
                  $material_names = '';
                  foreach ($results as $result) {
                  $material_names .= $result->material_name . ', ';
                  }
                  $material_names = rtrim($material_names, ', ');

                  $data_pdf = [
                    'master_name' => $master_name->name,
                    'maker_no' => strtoupper($postData["maker_no"]),
                    'material_names' => $material_names,
                    'qnty' => $stock_q,
              ];

                  $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                  $pdf->setPrintHeader(false);
                  $pdf->setPrintFooter(false);
                  $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT, true);
                  //$pdf->SetFont('helvetica', '', 10);
                  $pdf->SetFont("times", "", 10);
                  $pdf_data = $this->load->view("making_pdf", $data_pdf, true);
                  $pdf->addPage();
                  $pdf->writeHTML($pdf_data, true, false, true, false, "");
                  $filename = strtoupper($postData["maker_no"]).".pdf";
                  $dir = APPPATH . "/maker/" . $data_pdf["master_name"] . "/";
                  if (!is_dir($dir)) {
                      mkdir($dir, 0777, true);
                  }
                  $save_path = $dir . $filename;
                  ob_end_clean();
                  // $pdf->Output($save_path, "I");
                  $pdf->Output($save_path, "F");
                    $this->session->set_flashdata(
                        "success",
                        "Material added successfully."
                    );
                    redirect("Making");
                } else {
                    $this->session->set_flashdata(
                        "failed",
                        "Some problem occurred, please try again."
                    );
                    $this->load->view("layout/header", $data);
                    // $data['purList'] = $this->Purchaser_model->get_all_purchaser();
                    $this->load->view("layout/menubar");
                    $this->load->view("making_add", $data);
                    $this->load->view("layout/footer");
                }
            }
        } elseif ($this->session->userdata("logged_in")) {
            $data["title"] = ucwords("Add new Material Page");
            $data["username"] = $this->session->userdata("logged_in");
            $data["matList"] = $this->Purchaser_model->get_all_material();
            $data["makList"] = $this->Making_model->get_last_maker_insider();
            // $data["makList"] = $this->Making_model->get_all_making();
            $data["custList"] = $this->Customer_model->get_mowner();
            $data["PurchaserList"] = $this->Customer_model->get_powner();


            $this->load->view("layout/header", $data);
            $this->load->view("layout/menubar");
            $this->load->view("making_add", $data);
            $this->load->view("layout/footer");
        } else {
            redirect("Welcome");
        }
    }

    //form to UPDATE PRODUCT
    public function edit($prod_id)
    {
        $cust_data = $this->Making_model->get_material_byID($prod_id);
        if (!$this->session->userdata("logged_in")) {
            redirect("Welcome");
        } elseif ($prod_id && $this->input->post("edit_making") == null) {
            $data["title"] = ucwords("Edit Making Details");
            $data["username"] = $this->session->userdata("logged_in");
            $data["prod"] = $cust_data;
            $data["purList"] = $this->Making_model->get_all_making();
            $data["matList"] = $this->Purchaser_model->get_all_material();
            $data["custList"] = $this->Customer_model->get_mowner();
            $data["PurchaserList"] = $this->Customer_model->get_powner();


            $this->load->view("layout/header", $data);
            $this->load->view("layout/menubar");
            $this->load->view("making_edit");
            $this->load->view("layout/footer");
        } elseif ($this->input->post("edit_making") != null) {
            // POST data
            $postData = $this->input->post();
            // $this->form_validation->set_rules('material_name', 'Material Name', 'trim|required');
            // $this->form_validation->set_rules('p_design_number', 'Design Number', 'trim|required|callback_regexValidate|edit_unique[products.design_number.' . $prod_id . ']');
            // $this->form_validation->set_rules('p_design_number', 'Design Number', 'trim|required');
            $this->form_validation->set_rules(
                "master_name",
                "Master Name",
                "trim|required"
            );
            // $this->form_validation->set_rules('p_price', 'Product Amount', 'trim|required|numeric');
            // $this->form_validation->set_rules('stock_q', 'Product in Stock', 'trim|required|numeric');
            // $this->form_validation->set_rules('pcs', 'Pcs', 'trim');
            // $this->form_validation->set_rules('meter', 'Meter', 'trim');
            // $this->form_validation->set_rules('product_exp', 'Expiry Da
            // $this->form_validation->set_rules('product_exp', 'Expiry Date', 'trim|required');
            // $this->form_validation->set_rules('price_total', 'Product Quantity', 'trim|required|numeric');

            if ($this->form_validation->run() == false) {
                $data["title"] = ucwords("Edit Making Details");
                $data["username"] = $this->session->userdata("logged_in");
                $data["purList"] = $this->Making_model->get_all_making();
                $data["matList"] = $this->Purchaser_model->get_all_material();
                $data["custList"] = $this->Customer_model->get_mowner();
                $data["PurchaserList"] = $this->Customer_model->get_powner();


                $data["prod"] = $cust_data;

                $this->load->view("layout/header", $data);
                $this->load->view("layout/menubar");
                $this->load->view("making_edit");
                $this->load->view("layout/footer");
            } else {
                $material_id = implode(
                    ",",
                    $this->input->post("material_name[]")
                );
                $material_id = trim($material_id, ",");
                $stock_q = implode(",", $this->input->post("stock_q[]"));
                $stock_q = trim($stock_q, ",");
                $data = [
                    "making_owner_id" => strtoupper($postData["master_name"]),
                    "material_id" => $material_id,
                    "stock" => $stock_q,
                ];
                $prod_id = $postData["prod_id"];

                $update = $this->Making_model->update_making($data, $prod_id);
                // $product_id = $this->db->insert_id();
                // $data2 = array(
                // 	'product_id' => $prod_id,
                // 	'stock_qty' => $postData['stock_q'],
                // 	// 'purchase_rate' => $postData['p_price'],
                // 	// 'p_design_number' => $postData['p_design_number'],
                // );
                // $Store = $this->Stock_model->update_record($data2,$prod_id,);

                if ($update != -1) {
                    $this->session->set_flashdata(
                        "success",
                        "Material details updated successfully."
                    );
                    redirect("Making");
                } else {
                    $this->session->set_flashdata(
                        "failed",
                        "Some problem occurred, please try again."
                    );
                    $data["title"] = ucwords("Edit Material Details");
                    $data["username"] = $this->session->userdata("logged_in");
                    $data["purList"] = $this->Making_model->get_all_making();
                    $data[
                        "matList"
                    ] = $this->Purchaser_model->get_all_material();
                    $data["custList"] = $this->Customer_model->get_mowner();
                    $data["PurchaserList"] = $this->Customer_model->get_powner();


                    $data["cust"] = $cust_data;
                    $this->load->view("layout/header", $data);
                    $this->load->view("layout/menubar");
                    $this->load->view("making_edit");
                    $this->load->view("layout/footer");
                }
            }
        }
    }

    // Logout from admin page
    public function logout()
    {
        $this->session->unset_userdata("logged_in");
        header("location:" . site_url("?status=loggedout"));
    }

    public function deleteMaking()
    {
        if ($this->input->post("row_id")) {
            $id = $this->input->post("row_id");
            $upd = $this->Making_model->delete_by_id($id);
            // $stk = $this->Stock_model->delete_by_id($id);
            if ($upd > 0) {
                $resp["status"] = "passed";
                $resp["result"] = "Material deleted successfully.";
            } else {
                $resp["status"] = "failed";
                $resp["result"] = "Some problem occurred, please try again";
            }
            echo json_encode($resp);
        }
    }

    public function quantityById()
    {
      $id = $this->input->post('material_id');
      $data = $this->Purchaser_model->get_pstock($id);
      echo json_encode($data);
    }

    //Download pdf Maker
  	public function download_pdf($master_id, $maker_no )
  	{
      $customer_id= $master_id;
      $this->db->select('*');
      $this->db->from('customers');
      $this->db->where('id',$customer_id);
      $query = $this->db->get();
      $master_name = $query->row();
      $cust_name = $master_name->name;
  		if(!$this->session->userdata('logged_in'))
  		{
  			redirect('Welcome');
  		}

  		elseif( $cust_name && $maker_no )
  		{
  			$pdf_file = APPPATH.'maker/'.rawurldecode($cust_name).'/'.$maker_no.'.pdf';
  			$file = $maker_no.'.pdf';

  			if (file_exists($pdf_file))
  			{
  				header("Content-Type: application/pdf");
  				header("Content-Disposition: attachment;filename=\"$file\"" );
  				readfile($pdf_file);
  			}
  			else
  			{
  				$this->session->set_flashdata('no_pdf', 'Sorry! file not found...');
  				redirect('Making');
  			}
  		}
  		else
  		{
  			$data['title'] = ucwords('Page not found');
          	$data['username'] = $this->session->userdata('logged_in');
  			$this->load->view('layout/header', $data);
  	        $this->load->view('layout/menubar');
  			$this->load->view('errors/html/error_404');
  			$this->load->view('layout/footer');
  		}
  	}


}
