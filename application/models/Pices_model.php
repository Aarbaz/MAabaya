<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

class Pices_model extends CI_Model {

    public function get_products_in_pcs()
    {
        return $this->db->get("product_pices");
    }
}