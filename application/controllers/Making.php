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

                $data = [
                    "material_id" => $material_id,
                    "master_id" => strtoupper($postData["master_name"]),
                    "stock" => $stock_q,
                ];

                $insert = $this->Making_model->add_material($data);
                $product_id = $this->db->insert_id();


                $material_ids = $this->input->post("material_name[]");
                $stocks = $this->input->post("stock_q[]");
                $oldstock = $this->input->post("stock_in[]");


                $master_id = $this->input->post("master_id");

                $m = 0;
                foreach ($stocks as $row) {
                    $dataStk["quantity"] = $oldstock[$m] - $stocks[$m];
                    $this->Purchaser_model->update_pstock_qty($dataStk,$master_id,$material_ids[$m]);

                    $dataMak["master_id"] = $master_id;
                    $dataMak["material_id"] = $material_ids[$m];
                    $dataMak["quantity"] = $stocks[$m];
                    $this->Making_model->add_making_qty($dataMak);

                    $m++;
                }

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
                    "master_id" => strtoupper($postData["master_name"]),
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
}
