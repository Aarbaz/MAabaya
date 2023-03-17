<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Purchaser extends CI_Controller
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
        // $this->load->model('Purchaser_model');
        $this->load->model("Stock_model");
        $this->load->model("Purchaser_model");
        $this->load->model("Customer_model");
        $this->load->library("tcpdf");
        $this->load->library("upload");
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
        // die();
        if ($this->session->userdata("logged_in")) {
            $data["title"] = ucfirst("Material List Page");
            $data["username"] = $this->session->userdata("logged_in");
            $data["purchaser"] = $this->Purchaser_model->get_all_purchasers();
            $data["matList"] = $this->Purchaser_model->get_all_material();
            $this->load->view("layout/header", $data);
            $this->load->view("layout/menubar");
            $this->load->view("purchaserList", $data);
            $this->load->view("layout/footer");
        } else {
            redirect("Welcome");
        }
    }
    // get from DB
    public function list_a_purchser($id)
    {
        $materials = $this->Purchaser_model->get_purchaser_byID($id);
        echo json_encode($materials);
    }

    //form to add new product
    public function add_new()
    {
        // $this->form_validation->set_rules('prod_name', 'Product Name', 'trim|required|callback_regexValidate|is_unique[products.product_name]',  array('is_unique' => 'This %s already exists.'));
        // $this->form_validation->set_rules('material_name', 'Material Name', 'trim|required');
        $this->form_validation->set_rules(
            "owner_name",
            "Owner Name",
            "trim|required"
        );
        // $this->form_validation->set_rules('p_price', 'Product Amount', 'trim|required|numeric');
        // $this->form_validation->set_rules('stock_q', 'Product Amount', 'trim|required|numeric');
        // $this->form_validation->set_rules('price_total', 'Product Quantity', 'trim|required|numeric');

        if ($this->input->post("add_purchaser") != null) {
            if ($this->form_validation->run() == false) {
                $data["title"] = ucwords("Add new Purcahser Page");
                $data["username"] = $this->session->userdata("logged_in");
                $data["purList"] = $this->Purchaser_model->get_last_purchaser_insider();
                $data["matList"] = $this->Purchaser_model->get_all_material();
                $data["custList"] = $this->Customer_model->get_powner();

                $this->load->view("layout/header", $data);
                $this->load->view("layout/menubar");
                $this->load->view("purchaser_add", $data);
                $this->load->view("layout/footer");
            } else {
                $postData = $this->input->post();
                $material_name = implode(",",$this->input->post("material_name[]"));
                $material_name = trim($material_name, ",");

                $qnty = implode(",", $this->input->post("stock_q[]"));
                $qnty = trim($qnty, ",");

                $rate = implode(",", $this->input->post("p_price[]"));
                $rate = trim($rate, ",");

                $amount = implode(",", $this->input->post("price_total[]"));
                $amount = trim($amount, ",");

                $data = [
                    "purchaser_owner_id" => strtoupper($postData["owner_name"]),
                    "material_id" => $material_name,
                    "price" => $rate,
                    "stock" => $qnty,
                    "total_amount" => $amount,
                    "purchaser_no" =>strtoupper($postData["purchaser_no"]),
                ];

                $insert = $this->Purchaser_model->add_purchaser($data);
                $purchaser_id = $this->db->insert_id();

                $material_id = $this->input->post("material_name[]");
                $stock = $this->input->post("stock_q[]");
                $price = $this->input->post("p_price[]");

                $i = 0;
                foreach ($stock as $row) {
                    $dataStk["materials_id"] = $material_id[$i];
                    $dataStk["quantity"] = $stock[$i];
                    $dataStk["price"] = $price[$i];
                    $dataStk["purchaser_id"] = $purchaser_id;
                    $dataStk["purchaser_owner_id"] = strtoupper($postData["owner_name"]);

                    $this->db->where('purchaser_owner_id', strtoupper($postData["owner_name"]));
                    $this->db->where('materials_id',$material_id[$i]);
            				$query = $this->db->get('purchaser_stock');
            				$row = $query->row();
            				if ($query->num_rows()) {
            					// If the product exists, update the quantity value in the database
            					$data3 = array(
            						'quantity' => $row->quantity + $stock[$i],
            						'price' => $price[$i]
            					);
            					// print_r($data2);
                      $this->db->where('purchaser_owner_id', strtoupper($postData["owner_name"]));
                      $this->db->where('materials_id',$material_id[$i]);
            					$this->db->update('purchaser_stock', $data3);
            				} else {
            					// If the product does not exist, insert a new row into the database
            					// $this->db->insert('purchaser_stock', array('p_design_number' => $product_id, 'stock_qty' => $quantity));
                      $this->Purchaser_model->add_purchaser_qty($dataStk);

            				}



                    $i++;
                }

                if ($insert > 0) {
                  $customer_id=   strtoupper($postData["owner_name"]);
                  $this->db->select('*');
                  $this->db->from('customers');
                  $this->db->where('id',$customer_id);
                  $query = $this->db->get();
                  $purchaser_name = $query->row();

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
                    'purchaser_name' => $purchaser_name->name,
                    'purchaser_no' => strtoupper($postData["purchaser_no"]),
                    'material_names' => $material_names,
                    'qnty' => $qnty,
                    "amount" => $amount,
                    'rate' => $rate,
              ];

									$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
									$pdf->setPrintHeader(false);
									$pdf->setPrintFooter(false);
									$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT, true);
									//$pdf->SetFont('helvetica', '', 10);
									$pdf->SetFont("times", "", 10);
									$pdf_data = $this->load->view("purchaser_pdf", $data_pdf, true);
									$pdf->addPage();
									$pdf->writeHTML($pdf_data, true, false, true, false, "");
									$filename = strtoupper($postData["purchaser_no"]).".pdf";
									$dir = APPPATH . "/purchaser/" . $data_pdf["purchaser_name"] . "/";
									if (!is_dir($dir)) {
											mkdir($dir, 0777, true);
									}
									$save_path = $dir . $filename;
									ob_end_clean();
									// $pdf->Output($save_path, "I");
									$pdf->Output($save_path, "F");
									$this->session->set_flashdata(
											"success",
											" Purchaser invoice created successfully...."
									);
									redirect("Purchaser/");

                } else {
                    $this->session->set_flashdata(
                        "failed",
                        "Some problem occurred, please try again."
                    );
                    $this->load->view("layout/header", $data);

                    $data["purList"] = $this->Purchaser_model->get_last_purchaser_insider();

                    $this->load->view("layout/menubar");
                    $this->load->view("purchaser_add", $data);
                    $this->load->view("layout/footer");
                }
            }
        } elseif ($this->session->userdata("logged_in")) {
            $data["title"] = ucwords("Add new Material Page");
            $data["username"] = $this->session->userdata("logged_in");
            $data["purList"] = $this->Purchaser_model->get_last_purchaser_insider();
            $data["matList"] = $this->Purchaser_model->get_all_material();
            $data["custList"] = $this->Customer_model->get_powner();

            $this->load->view("layout/header", $data);
            $this->load->view("layout/menubar");
            $this->load->view("purchaser_add", $data);
            $this->load->view("layout/footer");
        } else {
            redirect("Welcome");
        }
    }

    //form to UPDATE PRODUCT
    public function edit($pur_id)
    {
        $cust_data = $this->Purchaser_model->get_purchaser_byID($pur_id);
        if (!$this->session->userdata("logged_in")) {
            redirect("Welcome");
        } elseif ($pur_id && $this->input->post("edit_purchaser") == null) {
            $data["title"] = ucwords("Edit Purchaser Details");
            $data["username"] = $this->session->userdata("logged_in");
            $data["pur"] = $cust_data;
            $data["purList"] = $this->Purchaser_model->get_all_purchaser();
            $data["matList"] = $this->Purchaser_model->get_all_material();
            $data["custList"] = $this->Customer_model->get_powner();
            $this->load->view("layout/header", $data);
            $this->load->view("layout/menubar");
            $this->load->view("purchaser_edit");
            $this->load->view("layout/footer");
        } elseif ($this->input->post("edit_purchaser") != null) {
            // POST data
            $postData = $this->input->post();
            // $this->form_validation->set_rules('material_name', 'Material Name', 'trim|required');
            // $this->form_validation->set_rules('p_design_number', 'Design Number', 'trim|required|callback_regexValidate|edit_unique[products.design_number.' . $prod_id . ']');
            // $this->form_validation->set_rules('p_design_number', 'Design Number', 'trim|required');
            $this->form_validation->set_rules(
                "owner_name",
                "Owner Name",
                "trim|required"
            );
            // $this->form_validation->set_rules('p_price', 'Product Amount', 'trim|required|numeric');
            // $this->form_validation->set_rules('stock_q', 'Product in Stock', 'trim|required');
            // $this->form_validation->set_rules('pcs', 'Pcs', 'trim');
            // $this->form_validation->set_rules('meter', 'Meter', 'trim');
            // $this->form_validation->set_rules('product_exp', 'Expiry Da
            // $this->form_validation->set_rules('product_exp', 'Expiry Date', 'trim|required');
            // $this->form_validation->set_rules('price_total', 'Product Quantity', 'trim|required|numeric');

            if ($this->form_validation->run() == false) {
                $data["title"] = ucwords("Edit Purchaser Details");
                $data["username"] = $this->session->userdata("logged_in");
                $data["purList"] = $this->Purchaser_model->get_all_purchaser();
                $data["matList"] = $this->Purchaser_model->get_all_material();
                $data["custList"] = $this->Customer_model->get_powner();

                $data["pur"] = $cust_data;
                $this->load->view("layout/header", $data);
                $this->load->view("layout/menubar");
                $this->load->view("purchaser_edit");
                $this->load->view("layout/footer");
            } else {
                $material_name = implode(
                    ",",
                    $this->input->post("material_name[]")
                );
                $material_name = trim($material_name, ",");

                $qnty = implode(",", $this->input->post("stock_q[]"));
                $qnty = trim($qnty, ",");

                $rate = implode(",", $this->input->post("p_price[]"));
                $rate = trim($rate, ",");

                $amount = implode(",", $this->input->post("price_total[]"));
                $amount = trim($amount, ",");

                $data = [
                    "purchaser_owner_id" => strtoupper($postData["owner_name"]),
                    "material_id" => $material_name,
                    "total_amount" => $amount,
                    "stock" => $qnty,
                    "price" => $rate,
                ];

                $purchaser_id = $postData["pur_id"];

                $update = $this->Purchaser_model->update_purchaser($data,$pur_id);

                // $purchaser_id = $this->db->insert_id();
                $material_id = $this->input->post("material_name[]");
                $stock = $this->input->post("stock_q[]");
                $price = $this->input->post("p_price[]");

                $j = 0;
                foreach ($stock as $row) {
                    $dataStk["material_id"] = $material_id[$j];
                    $dataStk["quantity"] = $stock[$j];
                    $dataStk["price"] = $price[$j];
                    $dataStk["purchaser_id"] = $purchaser_id;


                    // $this->db->where('purchaser_owner_id', strtoupper($postData["owner_name"]));
                    // $this->db->where('material_id',$material_id[$j]);
            				// $query = $this->db->get('purchaser_stock');
            				// $row = $query->row();
            				// if ($query->num_rows()) {
            				// 	// If the product exists, update the quantity value in the database
            				// 	$data3 = array(
            				// 		'quantity' => $row->quantity + $stock[$j],
            				// 		'price' => $price[$j]
            				// 	);
            				// 	// print_r($data2);
                    //   $this->db->where('purchaser_owner_id', strtoupper($postData["owner_name"]));
                    //   $this->db->where('material_id',$material_id[$j]);
            				// 	$this->db->update('purchaser_stock', $data3);
            				// } else {
            				// 	// If the product does not exist, insert a new row into the database
            				// 	// $this->db->insert('purchaser_stock', array('p_design_number' => $product_id, 'stock_qty' => $quantity));
                    //   $this->Purchaser_model->add_purchaser_qty($dataStk);
                    //
                    //
            				// }


                    $this->Purchaser_model->update_purchaser_qty($dataStk,$purchaser_id,$material_id[$j]);
                    $j++;
                }

                if ($update != -1) {
                    $this->session->set_flashdata(
                        "success",
                        "Material details updated successfully."
                    );
                    redirect("Purchaser");
                } else {
                    $this->session->set_flashdata(
                        "failed",
                        "Some problem occurred, please try again."
                    );
                    $data["title"] = ucwords("Edit Material Details");
                    $data["username"] = $this->session->userdata("logged_in");
                    $data[
                        "purList"
                    ] = $this->Purchaser_model->get_all_purchaser();
                    $data[
                        "matList"
                    ] = $this->Purchaser_model->get_all_material();
                    $data["custList"] = $this->Customer_model->get_powner();

                    $data["cust"] = $cust_data;
                    $this->load->view("layout/header", $data);
                    $this->load->view("layout/menubar");
                    $this->load->view("purchaser_edit");
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

    public function deletePurchaser()
    {
        if ($this->input->post("row_id")) {
            $id = $this->input->post("row_id");
            $upd = $this->Purchaser_model->delete_by_id($id);
            $stk = $this->Purchaser_model->delete_pstock($id);
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

    public function getPurchaserDetail()
    {
        $pp_id = $this->input->post("p_id");
        $detail = $this->Purchaser_model->getPurchaserDetailbyId($pp_id);
        echo json_encode($detail);
    }

    public function add_material()
    {
        $this->form_validation->set_rules(
            "material_name",
            "Material name",
            "required"
        );
        if ($this->form_validation->run() == false) {
            $this->index();
        } else {
            $material_name = $this->input->post("material_name");
            $id = $this->input->post("id");
            $data = [
                "material_name" => $material_name,
            ];

            if ($id) {
                $data = [
                    "id" => $id,
                    "material_name" => $material_name,
                ];
                $insert = $this->Purchaser_model->update_material($id, $data);
            } else {
                $insert = $this->Purchaser_model->create_material($data);
            }

            if ($insert == true) {
                $this->session->set_flashdata(
                    "success",
                    "Added successfully...."
                );
                redirect("Purchaser/add_new");
            } else {
                $this->session->set_flashdata(
                    "fail",
                    "Sorry! there was some error."
                );
                redirect(base_url("/index.php/Purchaser"));
            }
        }
    }


    //Download pdf Purchaser
  	public function download_pdf($purchaser_id, $purchaser_no)
  	{
      $customer_id= $purchaser_id;
      $this->db->select('*');
      $this->db->from('customers');
      $this->db->where('id',$customer_id);
      $query = $this->db->get();
      $purchaser_name = $query->row();
      $cust_name = $purchaser_name->name;
  		if(!$this->session->userdata('logged_in'))
  		{
  			redirect('Welcome');
  		}

  		elseif( $cust_name && $purchaser_no )
  		{
  			$pdf_file = APPPATH.'purchaser/'.rawurldecode($cust_name).'/'.$purchaser_no.'.pdf';
  			$file = $purchaser_no.'.pdf';

  			if (file_exists($pdf_file))
  			{
  				header("Content-Type: application/pdf");
  				header("Content-Disposition: attachment;filename=\"$file\"" );
  				readfile($pdf_file);
  			}
  			else
  			{
  				$this->session->set_flashdata('no_pdf', 'Sorry! file not found...');
  				redirect('Purchaser');
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
