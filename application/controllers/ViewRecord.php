<?php  
defined('BASEPATH') OR exit('No direct script access allowed');

Class ViewRecord extends CI_Controller{

    function __construct()
    {
        parent::__construct();
        $this->load->model("ViewRecordModel");
        $this->load->library('session');
              
    }
    public function index(){
        
        // Get user ID from session
        $user_id = $this->session->userdata('user_id');

        // Fetch orders from the model
        $data['record'] = $this->ViewRecordModel->getUsers($user_id);

        $this->load->view("viewrecord", $data);

        if ($this->session->flashdata('message')) {
            $data['message'] = $this->session->flashdata('message');
        }


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