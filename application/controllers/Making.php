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
        $this->load->model("History_model");
        $this->load->model("Stock_model");
        $this->load->library("tcpdf");
        $this->load->library("upload");
        $this->load->model('Balance_model');
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
        $this->form_validation->set_rules(
            "material_name[]",
            "Material Name",
            "trim|required"
        );
        $this->form_validation->set_rules(
            "stock_q[]",
            "Quantity",
            "trim|required"
        );

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
                if ($postData["bill_date"]) {
                    $date = $postData["bill_date"];
                } else {
                    $date = date("Y-m-d");
                }
                $data = [
                    "material_id" => $material_id,
                    // "purchaser_owner_id" => $master_id,
                    "making_owner_id" => $master_name,
                    "stock" => $stock_q,
                    "maker_no" => strtoupper($postData["maker_no"]),
                    "create_date" => $date,
                ];

                $insert = $this->Making_model->add_material($data);

                $product_id = $this->db->insert_id();

                
                $material_ids = $this->input->post("material_name[]");
                $stocks = $this->input->post("stock_q[]");
                $oldstock = $this->input->post("stock_in[]");

                $makeInvoiceId = strtoupper($postData["maker_no"]);
                $data_ledger = array(
                    'customer' => $master_name,
                    'invoice' => $makeInvoiceId,
                    'entry_from' => 2,
                    'dated' => date('Y-m-d H:i:s')
                );
                // $insert = $this->Challan_model->create_balance($data_balance);
                // $insert = $this->Balance_model->add_customer_ledger($data_ledger);

                $m = 0;
                foreach ($stocks as $row) {

                    if ($oldstock[$m] != ' ') {
                        $dataStk["quantity"] = (float) $oldstock[$m] - (float) $stocks[$m];

                        $this->Purchaser_model->update_pstock_qty($dataStk, $material_ids[$m]);

                        // $dataDStk["stock_qty"] = $oldstock[$m] - $stocks[$m];
                        // $this->Purchaser_model->update_Dstock($dataDStk,$material_ids[$m]);
                    } else {
                        $null_value = "0";
                        $dataMtk["materials_id"] = $material_ids[$m];
                        $dataMtk["quantity"] = (float) $null_value - (float) $stocks[$m];
                        $dataMtk["price"] = '';
                        $dataMtk["purchaser_id"] = '';
                        $dataMtk["purchaser_owner_id"] = '';
                        $this->Purchaser_model->add_purchaser_qty($dataMtk);

                    }

                    $dataMak["maker_id"] = $product_id;
                    $dataMak["making_owner_id"] = $master_name;
                    $dataMak["materials_id"] = $material_ids[$m];
                    $dataMak["quantity"] = $stocks[$m];

                    $this->db->where('materials_id', $material_ids[$m]);
                    $querys = $this->db->get('maker_stock');
                    $rows = $querys->row();
                    if ($querys->num_rows()) {
                        $data3 = array(
                            'quantity' => (float)$rows->quantity + (float)$stocks[$m],
                        );
                        $this->db->where('materials_id', $material_ids[$m]);
                        $this->db->update('maker_stock', $data3);
                    } else {
                        $this->Making_model->add_making_qty($dataMak);
                    }

                    $dataStk["quantity"] = (float)$oldstock[$m] - (float)$stocks[$m];
                    $this->Purchaser_model->update_pstock_qty($dataStk,$material_ids[$m]);

                    $this->db->where('product_id',$material_ids[$m]);
                    $query1 = $this->db->get('stock');
                    $rows = $query1->row();
                    
                    if ($query1->num_rows()) {
                      // If the product exists, update the quantity value in the database
                      $data_return = array(
                        'stock_qty' => (float)$rows->stock_qty - (float)$stocks[$m],
                        // 'price' => $price[$i]
                      );
                      $this->db->where('product_id',$material_ids[$m]);
                      $this->db->update('stock', $data_return);
                    }


                    $m++;
                }

                $json_data = json_encode($data);
                
                if ($insert > 0) {

                    /****************** Store in STOCK table ******************************/
                $material_ids = $this->input->post("material_name[]");
                $stock_quantities = $this->input->post("stock_q[]");

                if (!empty($material_ids) && !empty($stock_quantities)) {
                    // Loop through the data and store each pair in the stock table
                    for ($i = 0; $i < count($material_ids); $i++) {
                        $entry_from = 2;
                        $user_id = $master_name;
                        $invoice_id = $postData["maker_no"];
                        $material_id = $material_ids[$i];
                        $in_out_qnty = -1 * $stock_quantities[$i];
                        
                        $updated_stock = $this->Stock_model->get_material_stock($material_id);
                        $stock = $updated_stock ? $updated_stock : (-1 * $in_out_qnty);

                        $this->History_model->insertStockEntry($entry_from, $user_id, $invoice_id, $material_id, $in_out_qnty, $stock, $json_data);
                    }
                }
                /************************* Store in STOCK table ***********************/

                    $customer_id = $master_name;
                    $this->db->select('*');
                    $this->db->from('customers');
                    $this->db->where('id', $customer_id);
                    $query = $this->db->get();
                    $master_name = $query->row();

                    $material_ids = implode(",", $this->input->post("material_name[]"));
                    $material_values = trim($material_ids, ",");
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
                        'master_id' => $master_name->id,
                        'master_name' => $master_name->name,
                        'maker_no' => strtoupper($postData["maker_no"]),
                        'material_names' => $material_names,
                        'qnty' => $stock_q,
                        'create_date' => $date,
                    ];

                    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                    $pdf->setPrintHeader(false);
                    $pdf->setPrintFooter(false);
                    $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT, true);
                    //$pdf->SetFont('helvetica', '', 10);
                    $pdf->SetFont("", "", 10);
                    $pdf_data = $this->load->view("making_pdf", $data_pdf, true);
                    $pdf->addPage();
                    $pdf->writeHTML($pdf_data, true, false, true, false, "");
                    $filename = strtoupper($postData["maker_no"]) . ".pdf";
                    $dir = APPPATH . "/maker/" . $data_pdf["master_id"] . "/";
                    if (!is_dir($dir)) {
                        mkdir($dir, 0777, true);
                    }
                    $save_path = $dir . $filename;
                    ob_end_clean();
                    // $pdf->Output($save_path, "I");
                    $pdf->Output($save_path, "F");
                    $this->session->set_flashdata(
                        "success",
                        "Making added successfully."
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

                $material_names_new = $this->input->post("material_name[]");
                $stock_q_new = $this->input->post("stock_q[]");
                $prod_id = $postData["prod_id"];
                $master_name = $postData["master_name"];

                // Retrieve the existing record from the database
                $existingData = $this->Making_model->get_data_by_id($prod_id); // Replace 'get_data_by_id' with the actual method in your model to retrieve the existing data
                $existing_material_names = explode(",", $existingData["material_id"]);
                $existing_stock_q = explode(",", $existingData["stock"]);

                for ($i = 0; $i < count($existing_material_names); $i++) {
                    $existing_material_id = $existing_material_names[$i];
                    $existing_stock = $existing_stock_q[$i];

                    // Check if the existing material ID is not present in the new data
                    if (!in_array($existing_material_id, $material_names_new)) {
                        $old_materials[] = [
                            "material_id" => $existing_material_id,
                            "stock_q" => $existing_stock
                        ];
                    }
                }

                $material_id = implode(",", $this->input->post("material_name[]"));
                $material_id = trim($material_id, ",");
                $stock_q = implode(",", $this->input->post("stock_q[]"));
                $stock_q = trim($stock_q, ",");
                if ($postData["bill_date"]) {
                    $date = $postData["bill_date"];
                } else {
                    $date = date("Y-m-d");
                }

                $data = [
                    "making_owner_id" => strtoupper($postData["master_name"]),
                    "material_id" => $material_id,
                    "stock" => $stock_q,
                    'create_date' => $date,
                ];
                // Now you can use the $old_materials array to identify old materials not included in the new data
                $json_data = json_encode($data);
                // $dhistoryData = $this->History_model->deletHistoryByMakerInvoiceId($postData["maker_no"]);

                $updatedMaterials = $this->input->post('material_name');

                $updatedQty = $this->input->post('stock_q');
                for ($i = 0; $i < count($updatedMaterials); $i++) {
                    $h_material_id = $updatedMaterials[$i];
                    $h_quantity = $updatedQty[$i];
                    
                    $entry_from = 2;
                        $user_id = $master_name;
                        $invoice_id = $postData["maker_no"];
                        $curr_material_id = $h_material_id;
                        $in_out_qnty = -1 * $h_quantity;
                        
                        $updated_stock = $this->Stock_model->get_material_stock($h_material_id);
                        $stock = $updated_stock ? $updated_stock : (-1 * $in_out_qnty);
    
                        $historyData = $this->History_model->insertStockEntry($entry_from, $user_id, $invoice_id, $curr_material_id, $in_out_qnty, $updated_stock, $json_data);
                }
                // get history record to delete old
                $maker_no = $this->input->post('maker_no');
                

                
                $prod_id = $postData["prod_id"];

                $update = $this->Making_model->update_making($data, $prod_id);

                foreach ($old_materials as $old_material) {
                    $entry_from = 2;
                    $user_id = $master_name;
                    $invoice_id = $postData["maker_no"];
                    $material_id = $old_material;
                    $in_out_qnty = -1 * $old_material["stock_q"];
                    
                   /*  $updated_stock = $this->Stock_model->get_material_stock($material_id);
                    $stock = $updated_stock ? $updated_stock : (-1 * $in_out_qnty); */
                    $postjson = [];
                    $postjson["entry_from"] = $entry_from;
                    $postjson["user_id"] = $user_id;
                    $postjson["invoice_id"] = $invoice_id;
                    $postjson["material_id"] = $material_id;
                    $postjson["in_out_qnty"] = $in_out_qnty;
                    $postjson["stock_q"] = $stock_q;

                    $historyData = $this->History_model->insertStockEntry($entry_from, $user_id, $invoice_id, $material_id, $in_out_qnty, $stock_q, $json_data);

                    $this->db->where('materials_id', $old_material["material_id"]);
                    $query = $this->db->get('purchaser_stock');
                    $row = $query->row();
                    if ($query->num_rows()) {
                        $data3 = array(
                            'quantity' => (float) $row->quantity + (float) $old_material["stock_q"],
                        );
                        $this->db->where('materials_id', $old_material["material_id"]);
                        $this->db->update('purchaser_stock', $data3);
                    }

                    $this->db->where('materials_id', $old_material["material_id"]);
                    $querys = $this->db->get('maker_stock');
                    $rows = $querys->row();
                    if ($querys->num_rows()) {
                        $data33 = array(
                            'quantity' => (float) $rows->quantity - (float) $old_material["stock_q"],
                        );


                        $this->db->where('materials_id', $old_material["material_id"]);
                        $this->db->update('maker_stock', $data33);
                    }

                    // history record
                       
                }
                // $product_id = $this->db->insert_id();
                $stockNew = $this->input->post("stock_qhidden[]");
                $stockqNew = $this->input->post("stock_q[]");
                $material_idNew = $this->input->post("material_name[]");
                $i = 0;
                foreach ($stockNew as $row) {
                    $MakStk["maker_id"] = '0';
                    $MakStk["making_owner_id"] = '0';
                    $MakStk["materials_id"] = $material_idNew[$i];
                    $MakStk["quantity"] = $stockqNew[$i];

                    $this->db->where('materials_id', $material_idNew[$i]);
                    $query = $this->db->get('purchaser_stock');
                    $row = $query->row();

                    $value_null = '0';
                    if ($stockNew[$i]) {
                        $diff = (float) $stockqNew[$i] - (float) $stockNew[$i];
                    } else {
                        $diff = (float) $stockqNew[$i] - (float) $value_null;
                    }

                    if ($query->num_rows()) {

                        if ($diff > 0) {
                            // echo "The difference is positive: " . $diff;	
                            // If the product exists, update the quantity value in the database
                            $data3 = array(
                                'quantity' => (float) $row->quantity - (float) $diff,
                            );
                        } elseif ($diff < 0) {
                            // echo "The difference is negative: " . abs($diff);
                            $data3 = array(
                                'quantity' => (float) $row->quantity + (float) abs($diff),
                            );
                        } else {
                            // echo "The difference is negative: " . abs($diff);
                            $data3 = array(
                                'quantity' => (float)$row->quantity - (float)abs($diff),
                            );
                        }

                        $this->db->where('materials_id', $material_idNew[$i]);
                        $this->db->update('purchaser_stock', $data3);
                    }

                    // if ($oldstock[$m] != ' ') {
                    //     $dataStk["quantity"] = (float)$oldstock[$m] - (float)$stocks[$m];

                    //     $this->Purchaser_model->update_pstock_qty($dataStk, $material_ids[$m]);

                    //     // $dataDStk["stock_qty"] = $oldstock[$m] - $stocks[$m];
                    //     // $this->Purchaser_model->update_Dstock($dataDStk,$material_ids[$m]);
                    // } 
                    else {
                        // $null_value= "0";
                        $dataMtk["materials_id"] = $material_idNew[$i];
                        $dataMtk["quantity"] = (float) $value_null - (float) $stockqNew[$i];
                        $dataMtk["price"] = '';
                        $dataMtk["purchaser_id"] = '';
                        $dataMtk["purchaser_owner_id"] = '';
                        $this->Purchaser_model->add_purchaser_qty($dataMtk);

                    }


                    $this->db->where('materials_id', $material_idNew[$i]);
                    $querys = $this->db->get('maker_stock');
                    $rows = $querys->row();
                    if ($querys->num_rows()) {
                        if ($stockNew[$i]) {
                            $diff = (float)$stockqNew[$i] - (float)$stockNew[$i];
                        } else {
                            $diff = (float)$stockqNew[$i] - '0';
                        }

                        if ($diff > 0) {
                            // If the product exists, update the quantity value in the database
                            $data3 = array(
                                'quantity' => (float)$rows->quantity + (float)$diff,
                            );
                        } elseif ($diff < 0) {
                            $data3 = array(
                                'quantity' => (float)$rows->quantity - (float)abs($diff),
                            );
                        } else {
                            // echo "The difference is negative: " . abs($diff);
                            $data3 = array(
                                'quantity' => (float)$row->quantity + (float)abs($diff),
                            );
                        }

                        $this->db->where('materials_id', $material_idNew[$i]);
                        $this->db->update('maker_stock', $data3);
                    } else {
                        $this->Making_model->add_making_qty($MakStk);
                    }
                    $i++;
                }

                $customer_id = $postData["master_name"];
                $this->db->select('*');
                $this->db->from('customers');
                $this->db->where('id', $customer_id);
                $query = $this->db->get();
                $master_name = $query->row();


                $this->db->select('*');
                $this->db->from('material');
                $this->db->where_in('id', $material_idNew);
                $query = $this->db->get();
                $results = $query->result();
                $material_names = '';
                foreach ($results as $result) {
                    $material_names .= $result->material_name . ', ';
                }
                $material_names = rtrim($material_names, ', ');


                $data_pdf = [
                    'master_id' => $master_name->id,
                    'master_name' => $master_name->name,
                    'maker_no' => strtoupper($this->input->post("maker_no")),
                    'material_names' => $material_names,
                    'qnty' => $stock_q,
                    'create_date' => $date,
                ];



                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                $pdf->setPrintHeader(false);
                $pdf->setPrintFooter(false);
                $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT, true);
                //$pdf->SetFont('helvetica', '', 10);
                $pdf->SetFont("", "", 10);
                $pdf_data = $this->load->view("making_pdf", $data_pdf, true);
                $pdf->addPage();
                $pdf->writeHTML($pdf_data, true, false, true, false, "");
                $filename = strtoupper($postData["maker_no"]) . ".pdf";
                $dir = APPPATH . "/maker/" . $data_pdf["master_id"] . "/";
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }
                $save_path = $dir . $filename;
                ob_end_clean();
                // $pdf->Output($save_path, "I");
                $pdf->Output($save_path, "F");



                // $stock_qhidden = implode(",", $this->input->post("stock_qhidden[]"));
                // $stock_qhidden = trim($stock_qhidden, ",");

                // $data_purchaser = [
                //     "quantity" => $diff,
                // ];
                // $update = $this->Purchaser_model->update_pstock_qty($data_purchaser, $material_id);
                $update = '0';
                if ($update != -1) {
                    $this->session->set_flashdata("success", "Material details updated successfully.");
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
    public function download_pdf($master_id, $maker_no)
    {
        $customer_id = $master_id;
        $this->db->select('*');
        $this->db->from('customers');
        $this->db->where('id', $customer_id);
        $query = $this->db->get();
        $master_name = $query->row();
        $cust_name = $master_name->name;
        if (!$this->session->userdata('logged_in')) {
            redirect('Welcome');
        } elseif ($cust_name && $maker_no) {
            $pdf_file = APPPATH . 'maker/' . rawurldecode($customer_id) . '/' . $maker_no . '.pdf';
            $file = $maker_no . '.pdf';

            if (file_exists($pdf_file)) {
                header("Content-Type: application/pdf");
                header("Content-Disposition: attachment;filename=\"$file\"");
                readfile($pdf_file);
            } else {
                $this->session->set_flashdata('no_pdf', 'Sorry! file not found...');
                redirect('Making');
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


}