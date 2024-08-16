<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

class Billing_model extends CI_Model {

    public function create_bill($data)
    {
        return $this->db->insert('billing', $data);
    }
}