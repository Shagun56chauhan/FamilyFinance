<?php
defined('BASEPATH') or exit('No direct script access allowed');





class AddRecord extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
          // Check if the user is logged in before accessing the page
          if (!$this->session->userdata('user_id')) {
            redirect('auth'); // Redirect to login page if not logged in
        }
        $this->load->model("AddRecordModel");
        $this->load->library('session');
        $this->load->helper('url');

    }




    public function index()
    {
        $data['vehicle_types'] = $this->AddRecordModel->getVehicleTypes();
        $this->load->view("addrecord", $data);

        if ($this->session->flashdata('message')) {
            $data['message'] = $this->session->flashdata('message');
        }
    }


    function submit_btn()
    {
        $user_id = $this->session->userdata('user_id');
        $type = $this->input->post('type');
        $reading = $this->input->post('reading');
        $date = $this->input->post('date');
        $remarks = $this->input->post('notes');

        // If remarks are empty, set it to an empty string
        if (empty($remarks)) {
            $remarks = '';
        }

 // Check if all required fields are filled
 if (empty($user_id) || empty($type) || empty($reading) || empty($date)) {
    // Set an error message if any required field is missing
    $this->session->set_flashdata('message', 'Please fill out all required fields.');
    redirect('addrecord');
    return;  // Stop further execution if validation fails
}

// Fetch the last reading for this user and vehicle type
$last_reading = $this->AddRecordModel->getLastReading($user_id, $type);

// Check if the new reading is greater than the last reading
if ($last_reading !== null && $reading <= $last_reading) {
    // Set an error message if the new reading is not greater
    $this->session->set_flashdata('message', 'Error: The new reading must be greater than the previous reading (' . $last_reading . ' km).');
    redirect('addrecord');
    return;  // Stop further execution if validation fails
    
}
// If all validations pass, save the data using the model
$this->AddRecordModel->submit_btn($user_id, $type, $reading, $date, $remarks);
// Set a flash message for success
$this->session->set_flashdata('message', 'Your Item Successfully Added!');

// Redirect back to the add record page
redirect('addrecord');
}

   
 




}