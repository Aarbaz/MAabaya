<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

class Check_login extends CI_Model {
	public function find_user($uname, $pass)
	{        	
       	$query = $this->db->get_where('users', array('username' => $uname, 'password' => $pass));
       	return  $query->result_array();
    }
}
