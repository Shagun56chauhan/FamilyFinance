<?php
defined('BASEPATH') or exit('No direct script access allowed');





class Expense extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("ExpenseModel");
        $this->load->library('session');
        $this->load->helper('url');

    }




    public function index()
    {

          // Get user ID from session
          $user_id = $this->session->userdata('user_id');

      
        $data['expense_types'] = $this->ExpenseModel->getUniqueExpenseTypes($user_id);

      
        $this->load->view("expense", $data);

        if ($this->session->flashdata('message')) {
            $data['message'] = $this->session->flashdata('message');
        }
    }


    function submit_btn()
    {
        $user_id = $this->session->userdata('user_id');
        $type = $this->input->post('type');
        $amount = $this->input->post('amount');
        $date = $this->input->post('date');
        $remarks = $this->input->post('notes');

        // If remarks are empty, set it to an empty string
        if (empty($remarks)) {
            $remarks = '';
        }
      



        $this->ExpenseModel->submit_btn($user_id, $type, $amount, $date, $remarks);
        $this->session->set_flashdata('message', 'Your Item Successfully Added!');




        redirect('expense');

    }



 

// Delete a particular row and redirect to the view page
public function delete($id) {
    $this->ExpenseModel->deleteExpense($id); // Call the delete function
    $this->session->set_flashdata('message', 'Expense deleted successfully!');
    redirect('viewexpense'); // Redirect to the view orders page
}



// edit expense

// Load the edit form with expense data pre-filled
public function edit($id) {
    $data['view'] = $this->ExpenseModel->getExpenseById($id);  // Fetch expense
    
    $data['id'] = $id;  // Pass the ID to the view
    if ($data['view'] && is_array($data['view'])) {
        $this->load->view('edit', $data);
    } else {
        $this->session->set_flashdata('message', 'Expense not found.');
        redirect('expense/index');  // Redirect if data not found
    }
}

// Update the expense details
public function update() {
    $id = $this->input->post('id');  // Get the hidden ID from the form

    $expenseData = [
        'type' => $this->input->post('type'),
        'amount' => $this->input->post('amount'),
        'created_at' => $this->input->post('date'),
        'remarks' => $this->input->post('notes')
    ];

    if ($this->ExpenseModel->updateExpense($id, $expenseData)) {
        $this->session->set_flashdata('message', 'Expense updated successfully!');
    } else {
        $this->session->set_flashdata('message', 'Failed to update expense.');
    }

    redirect('expense/edit/' . $id);  // Redirect back to the edit form
}



}