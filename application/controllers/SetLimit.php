<?php
defined('BASEPATH') or exit('No direct script access allowed');





class SetLimit extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
         // Check if the user is logged in before accessing the page
        if (!$this->session->userdata('user_id')) {
            redirect('Auth'); // Redirect to login page if not logged in
        }
        $this->load->model("SetLimitModel");
        $this->load->library('session');
        $this->load->helper('url');

    }




    public function index()
    {

          // Get user ID from session
          $user_id = $this->session->userdata('user_id');

      
        $data['expense_types'] = $this->SetLimitModel->getUniqueExpenseTypes();

      
        $this->load->view("setlimit", $data);

        if ($this->session->flashdata('message')) {
            $data['message'] = $this->session->flashdata('message');
        }
    }


    function submit_btn()
    {
        $user_id = $this->session->userdata('user_id');
        $type = $this->input->post('type');
        $set_limit = $this->input->post('set_limit');
        $date = $this->input->post('date');
        
        $this->SetLimitModel->submit_btn($user_id, $type, $set_limit, $date);
        $this->session->set_flashdata('message', 'Your Item Successfully Added!');
        redirect('SetLimit');

    }
}