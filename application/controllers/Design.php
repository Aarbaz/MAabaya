<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Design extends CI_Controller
{
    public function __construct()
	{
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('url'); 
        $this->load->model('Design_model');
    }

    public function index(){
		if ($this->session->userdata('logged_in')) {
			$data['title'] = ucfirst('Designs');
			$data['username'] = $this->session->userdata('logged_in');
			$data['designs'] = $this->Design_model->get_all_design();

			$this->load->view('layout/header', $data);
			$this->load->view('layout/menubar');
			$this->load->view('DesignList.php', $data);
			$this->load->view('layout/footer');
		} else {
			redirect('Welcome');
		}
	}
    public function add_new(){

        $this->form_validation->set_rules('design_number', 'Design number', 'required');
        if ($this->form_validation->run() == false)
		{			
			$this->index();		
		}
		else
		{
            $design_number = $this->input->post('design_number');
            $id = $this->input->post('id');
            $data = array(
                'design_num' => $design_number,
            );
           /*  print_r($id);
            die(); */
            if ($id) {
                $data = array(
                    'id' => $id,
                    'design_num' => $design_number,
                );
                $insert = $this->Design_model->update_record($id,$data);
            }else{
                $insert = $this->Design_model->create_record($data);
            }

            if($insert == true)
			{
                $this->session->set_flashdata('success', 'Added successfully....');
				redirect('Design/');
			}
			else
			{
				$this->session->set_flashdata('fail', "Sorry! there was some error.");
				redirect(base_url('/index.php/Design'));			
            }
        }
    }

    public function fetch_by_id($id){
        //$id = $this->input->post('id');
        $id = $this->Design_model->get_design_byID($id);
    }
    public function deleteDesign()
	{
		if( $this->input->post('id'))
		{			
			$id = $this->input->post('id');
			$upd = $this->Design_model->delete_by_id($id);
			if($upd > 0)
			{
				$resp['status'] = 'passed';
				$resp['result'] = 'Design deleted successfully.';
			}
			else
			{
				$resp['status'] = 'failed';
				$resp['result'] = 'Some problem occurred, please try again';
			}
			echo json_encode($resp);
		}
	}
}
?>