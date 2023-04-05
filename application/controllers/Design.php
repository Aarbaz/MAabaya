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


  // Add new MATERIAL form_new

  public function add_design()
  {
      $this->form_validation->set_rules(
          "design_name",
          "Design Number",
          "required"
      );
      if ($this->form_validation->run() == false) {
          $data["title"] = ucwords("Add new Design Page");
          $data["username"] = $this->session->userdata("logged_in");
          // $data["purList"] = $this->Purchaser_model->get_last_purchaser_insider();
          // $data["matList"] = $this->Purchaser_model->get_all_material();
          // $data["custList"] = $this->Customer_model->get_powner();

          $this->load->view("layout/header", $data);
          $this->load->view("layout/menubar");
          $this->load->view("design_add", $data);
          $this->load->view("layout/footer");

      } else {
          $design_name = $this->input->post("design_name");
          $id = $this->input->post("id");
          $data = [
              "design_num" => $design_name,
          ];

          if ($id) {
              $data = [
                  "id" => $id,
                  "design_num" => $design_name,
              ];
              $insert = $this->Design_model->update_record($id, $data);
          } else {
              $insert = $this->Design_model->create_record($data);
          }

          if ($insert == true) {
              $this->session->set_flashdata(
                  "success",
                  "Added successfully...."
              );
              redirect("Design");
          } else {
              $this->session->set_flashdata(
                  "fail",
                  "Sorry! there was some error."
              );
              redirect(base_url("/index.php/Design"));
          }
      }
  }


  //form to UPDATE design
  public function edit($cust_id )
  {
    // $cust_data = $this->Customer_model->get_customer_byID($cust_id);
    $cust_data = $this->Design_model->get_design($cust_id);
    // print_r($cust_id);
    // die();

    if(!$this->session->userdata('logged_in'))
    {
      redirect('Welcome');
    }

    elseif( $cust_id && $this->input->post('edit_design') == NULL )
    {
      if($cust_data)
        {
          $data['title'] = 'Edit Design Details';
            $data['username'] = $this->session->userdata('logged_in');
            $data['cust'] = $cust_data;
            $this->load->view('layout/header', $data);
            $this->load->view('layout/menubar');
          $this->load->view('design_edit');
          $this->load->view('layout/footer');
        }
      else
      {
          $data['title'] = 'Page not found';
          $data['username'] = $this->session->userdata('logged_in');
          $this->load->view('layout/header', $data);
          $this->load->view('layout/menubar');
          $this->load->view('errors/html/error_404');
          $this->load->view('layout/footer');
      }
  }
    elseif( $this->input->post('edit_design') != NULL )
    {
      $postData = $this->input->post();

      $this->form_validation->set_rules('design_name', 'Design Number', 'required|alpha_numeric_spaces');
    // $this->form_validation->set_rules('owner_name', 'Owner Name', 'required|alpha_numeric_spaces');
    // $this->form_validation->set_rules('city', 'City', 'alpha_numeric_spaces');
    // $this->form_validation->set_rules('phone', 'Phone number', 'numeric|min_length[10]|max_length[12]');
    // $this->form_validation->set_rules('email', 'Email ID', 'valid_email');

      if ($this->form_validation->run() == false)
      {
      $data['title'] = 'Edit Design Details';
      $data['username'] = $this->session->userdata('logged_in');
      $data['cust'] = $cust_data;

      $this->load->view('layout/header', $data);
      $this->load->view('layout/menubar');
      $this->load->view('design_edit');
      $this->load->view('layout/footer');
        }
    else
    {
      $designl_name = $this->input->post('design_name');
      $id = $this->input->post('cust_id');
      $data = array(
          'design_num' => $designl_name,
      );
      if ($id) {
          $data = array(
              'id' => $id,
              'design_num' => $designl_name,
          );
          $insert = $this->Design_model->update_record($id,$data);
      }
      else{
          $insert = $this->Design_model->create_record($data);
      }




      if($insert != -1)
      {
        $this->session->set_flashdata('success', 'Design details updated successfully.');
        redirect('Design');
      }
      else
      {
        $this->session->set_flashdata('failed', 'Some problem occurred, please try again.');
        $data['title'] = ucwords('Edit Material Details');
        $data['username'] = $this->session->userdata('logged_in');
        $data['cust'] = $cust_data;
        $this->load->view('layout/header', $data);
        $this->load->view('layout/menubar');
        $this->load->view('design_edit');
        $this->load->view('layout/footer');
      }
      }
    }
  }

    public function add_new_old(){

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
