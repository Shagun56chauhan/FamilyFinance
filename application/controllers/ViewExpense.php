<?php  
defined('BASEPATH') OR exit('No direct script access allowed');

Class ViewExpense extends CI_Controller{

    function __construct()
    {
        parent::__construct();
        $this->load->model("ViewExpenseModel");
        $this->load->library('session');
              
    }
    public function index(){
        
        // Get user ID from session
        $user_id = $this->session->userdata('user_id');

        // Fetch orders from the model
        $data['view'] = $this->ViewExpenseModel->getUsers($user_id);

        $this->load->view("viewexpense", $data);

        if ($this->session->flashdata('message')) {
            $data['message'] = $this->session->flashdata('message');
        }


    }

}