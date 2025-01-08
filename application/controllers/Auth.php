<?php
defined('BASEPATH') or exit('No direct script access allowed');





class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("AuthModel");
        $this->load->library('session');
        $this->load->helper('url');
    }




    public function index()
    {
        $data['auth'] = $this->AuthModel->getUsers();
        $this->load->view("auth", $data);

        if ($this->session->flashdata('message')) {
            $data['message'] = $this->session->flashdata('message');
        }
    }

    public function login()
    {
        $name = $this->input->post('name');
        $password = $this->input->post('password');

        // Validate input
        if (empty($name)) {
            $this->session->set_flashdata('message', 'User Name is required');
            redirect('auth');
        } elseif (empty($password)) {
            $this->session->set_flashdata('message', 'Password is required');
            redirect('auth');
        } elseif (strlen($password) < 6) {
            $this->session->set_flashdata('message', 'Password must be at least 6 characters');
            redirect('auth');
        } else{
             // Check if the user exists
             $user = $this->AuthModel->getUserByName($name);

             // Verify the hashed password
        if ($user && password_verify($password, $user['password'])) {
            $this->session->set_userdata('user_id', $user['id']); // Store user ID
            $this->session->set_userdata('name', $name); // Store username in session
            $this->session->set_flashdata('message', 'You have successfully logged in!');
            redirect('expense');
        } else {
            $this->session->set_flashdata('message', 'Invalid username or password');
            redirect('auth');
        }
           
                 
        }
    }

  
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth'); // Redirect to login page
    }
}
