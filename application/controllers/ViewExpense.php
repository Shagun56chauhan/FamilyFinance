<?php  
defined('BASEPATH') OR exit('No direct script access allowed');

Class ViewExpense extends CI_Controller{

    function __construct()
    {
        parent::__construct();
          // Check if the user is logged in before accessing the page
          if (!$this->session->userdata('user_id')) {
            redirect('auth'); // Redirect to login page if not logged in
        }
        $this->load->model("ViewExpenseModel");
        $this->load->library('session');
              
    }
    public function index(){
        
        // Get user ID from session
        $user_id = $this->session->userdata('user_id');


         // Fetch all expenses and unique expense types for the user
         $data['view'] = [];
         $data['total_expense'] = 0; // Initialize total expense
        

         
        // Calculate total expense for all expenses (not filtered by type)
        $data['total_expense'] = $this->ViewExpenseModel->calculateTotalExpense($user_id);
      

         

         $selected_type = $this->input->post('type');
         
        $data['expense_types'] = $this->ViewExpenseModel->getUniqueExpenseTypes($user_id);
       


        $data['view'] = $this->ViewExpenseModel->getUsers($user_id, $selected_type); // Fetch filtered data
       

        // Calculate total expense for the selected type
        $data['selected_type_expense'] = $this->ViewExpenseModel->calculateTotalExpense($user_id, $selected_type);
        

         // Pass the selected type to the view
         $data['selected_type'] = $selected_type;

        $this->load->view("viewexpense", $data);

        if ($this->session->flashdata('message')) {
            $data['message'] = $this->session->flashdata('message');
        }


    }
    

   

}