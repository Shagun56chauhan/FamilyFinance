<?php  
defined('BASEPATH') OR exit('No direct script access allowed');

Class ViewRecord extends CI_Controller{

    function __construct()
    {
        parent::__construct();
          // Check if the user is logged in before accessing the page
          if (!$this->session->userdata('user_id')) {
            redirect('auth'); // Redirect to login page if not logged in
        }
        $this->load->model("ViewRecordModel");
        $this->load->library('session');
              
    }
    public function index(){
        
        // Get user ID from session
        $user_id = $this->session->userdata('user_id');
        $selected_type = $this->input->get('type');  // Get selected type from the form

        // Fetch orders from the model
        $data['records'] = $this->ViewRecordModel->getUsers($user_id ,$selected_type);

        // table 2

        $data['record_types'] = $this->ViewRecordModel->getVehicleTypes($user_id); // Keep the vehicle types for the dropdown
       
       

        // Calculate the total distance for the selected vehicle type and user
        $data['total_distance'] = $this->ViewRecordModel->getTotalDistance($selected_type, $user_id);


   
      // Pass the selected type to the view
      $data['selected_type'] = $selected_type;

        // table2

       

        if ($this->session->flashdata('message')) {
            $data['message'] = $this->session->flashdata('message');
        }

        $this->load->view("viewrecord", $data);

    }


    
// Delete a particular row and redirect to the view page
public function delete($id) {
    $this->ViewRecordModel->deleteExpense($id); // Call the delete function
    $this->session->set_flashdata('message', 'Expense deleted successfully!');
    redirect('viewrecord'); // Redirect to the view orders page
}



// edit expense

// Load the edit form with expense data pre-filled
public function edit($id) {
    $data['record'] = $this->ViewRecordModel->getExpenseById($id);  // Fetch expense
    
    $data['id'] = $id;  // Pass the ID to the view
    if ($data['record'] && is_array($data['record'])) {
        $this->load->view('viewedit', $data);
    } else {
        $this->session->set_flashdata('message', 'Expense not found.');
        redirect('addrecord/index');  // Redirect if data not found
    }
}

// Update the expense details
public function update() {
    $id = $this->input->post('id');  // Get the hidden ID from the form

    $recordData = [
        'type' => $this->input->post('type'),
        'reading' => $this->input->post('reading'),
        'created_at' => $this->input->post('date'),
        'remarks' => $this->input->post('notes')
    ];

    if ($this->ViewRecordModel->updateExpense($id, $recordData)) {
        $this->session->set_flashdata('message', 'Record updated successfully!');
    } else {
        $this->session->set_flashdata('message', 'Failed to update Record.');
    }

    redirect('viewrecord/edit/' . $id);  // Redirect back to the edit form
}


}