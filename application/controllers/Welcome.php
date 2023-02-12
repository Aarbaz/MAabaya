<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	/*
	 1. by default index() is caleld and login form is displayed.
	 2. after filling login details, login_check() is called to check and redirect users

	*/

	public function __construct() 
    {
        parent::__construct();       
        $this->load->model('Check_login');
    }

	public function index($data = null )
	{
		$data['title'] = ucfirst('Welcome'); // Capitalize the first letter		
   		$this->load->view('layout/header', $data);
		$this->load->view('welcome');		
	}

	public function login_check()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('emailid', 'User Name', 'required', array('required'=>'Please enter %s.'));
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == false)
		{			
			$this->index();
		}
		else
		{
			$username = $this->input->post('emailid');
			$password = $this->input->post('password');
			$chk = $this->Check_login->find_user($username, $password);			
			if( count($chk) > 0)
			{				
				$this->session->set_userdata('logged_in', $username);
				redirect('Dashboard'); //redirect to dashboard controller
			}
			else
			{				
				$data['login_err'] = "Invalid username OR password!";	   				
				$this->index($data);							
			}
		}			 	
	}


}
