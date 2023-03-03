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
        $this->load->library("tcpdf");
        $this->load->library("upload");
        //$this->load->helper('pdf_helper');
        $this->load->helper("url");
        // $this->load->model('Stock_model');
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

            $this->load->view("layout/header", $data);
            $this->load->view("layout/menubar");
            $this->load->view("makingList", $data);
            $this->load->view("layout/footer");
        } else {
            redirect("Welcome");
        }
    }
    // get from DB
    // public function list_a_product($id)
    // {
    // 	$materials = $this->Product_model->get_product_byID($id);
    // 	echo json_encode($materials);
    // }

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
                // $data['purList'] = $this->Product_model->get_all_purchaser();
                $this->load->view("layout/header", $data);
                $this->load->view("layout/menubar");
                $this->load->view("making_add", $data);
                $this->load->view("layout/footer");
            } else {
                // POST data
                $postData = $this->input->post();

                $material_name = implode(",",$this->input->post("material_name[]"));
                $material_name = trim($material_name, ",");
                $stock_q = implode(",", $this->input->post("stock_q[]"));
                $stock_q = trim($stock_q, ",");

                $data = [
                    "material_name" => $material_name,
                    "master_name" => strtoupper($postData["master_name"]),
                    "stock" => $stock_q,
                ];

                $insert = $this->Making_model->add_material($data);
                $product_id = $this->db->insert_id();

                $data_pdf = [
                    "material_name" => $material_name,
                    "master_name" => strtoupper($postData["master_name"]),
                    "stock" => $stock_q,
                ];

								if($insert == true)
								{
									$this->load->library('tcpdf/tcpdf.php');

									$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
									$pdf->setPrintHeader(false);
									$pdf->setPrintFooter(false);
									$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT, true);
									//$pdf->SetFont('helvetica', '', 10);
									$pdf->SetFont("times", "", 10);
									$pdf_data = $this->load->view("making_pdf", $data_pdf, true);
									$pdf->addPage();
									$pdf->writeHTML($pdf_data, true, false, true, false, "");

									// $filename = $this->input->post("invoice_no") . ".pdf";
									$filename = "asads" . ".pdf";
									$dir = APPPATH . "/invoice/" . $data_pdf["master_name"] . "/";
									if (!is_dir($dir)) {
											mkdir($dir, 0777, true);
									}
									$save_path = $dir . $filename;
									ob_end_clean();
									$pdf->Output($save_path, "I");
									$pdf->Output($save_path, "F");
									//file_put_contents($save_path, $pdf);
									$this->session->set_flashdata(
											"success",
											"Invoice created successfully...."
									);
									redirect("Making/");
									// $data2 = array(
									// 	'product_id' => $product_id,
									// 	'stock_qty' => $postData['stock_q'],
									// 	// 'purchase_rate' => $postData['p_price'],
									// 	// 'p_design_number' => $postData['p_design_number'],
									// );
									// // $Store = $this->Stock_model->add_record($data2);

									if ($insert > 0) {
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
											// $data['purList'] = $this->Product_model->get_all_purchaser();
											$this->load->view("layout/menubar");
											$this->load->view("making_add", $data);
											$this->load->view("layout/footer");
									}
								}

								else
								{
									$this->session->set_flashdata('fail', "Sorry! there was some error.");
									redirect(base_url('/index.php/Making/add_new'));
								}

            }
        } elseif ($this->session->userdata("logged_in")) {
            $data["title"] = ucwords("Add new Material Page");
            $data["username"] = $this->session->userdata("logged_in");
            // $data['purList'] = $this->Product_model->get_all_purchaser();
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
        // echo 'str';
        // die();
        $cust_data = $this->Making_model->get_material_byID($prod_id);
        if (!$this->session->userdata("logged_in")) {
            redirect("Welcome");
        } elseif ($prod_id && $this->input->post("edit_making") == null) {
            $data["title"] = ucwords("Edit Making Details");
            $data["username"] = $this->session->userdata("logged_in");
            $data["prod"] = $cust_data;
            $data["purList"] = $this->Making_model->get_all_making();

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
                $data["prod"] = $cust_data;

                $this->load->view("layout/header", $data);
                $this->load->view("layout/menubar");
                $this->load->view("making_edit");
                $this->load->view("layout/footer");

            } else {
                // print_r($postData['master_name']);
                // die();
                $material_name = implode(",",$this->input->post("material_name[]"));
                $material_name = trim($material_name, ",");
                $stock_q = implode(",", $this->input->post("stock_q[]"));
                $stock_q = trim($stock_q, ",");
                $data = [
                    "master_name" => strtoupper($postData["master_name"]),
                    "material_name" => $material_name,
                    "stock" => $stock_q,
                ];
                $prod_id = $postData["prod_id"];
                // print_r($data);
                // die();
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

    // public function getProductDetail()
    // {
    // 	$pp_id = $this->input->post('p_id');
    // 	$detail = $this->Product_model->getProductDetailbyId($pp_id);
    //
    // 	//print_r($detail);
    //
    // 	echo json_encode($detail);
    // }
}
