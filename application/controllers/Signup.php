<?php
defined('BASEPATH') or exit('No direct script access allowed');





class Signup extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("SignupModel");
        $this->load->library('session');
        $this->load->helper('url');
    }



    public function index()
    {
        $data['signup'] = $this->SignupModel->getUsers();
        $this->load->view("signup", $data);

        if ($this->session->flashdata('message')) {
            $data['message'] = $this->session->flashdata('message');
        }
    }

    public function submit_btn()
    {
        $name = $this->input->post('name');
        $username = $this->input->Post('user_name');
        $password = $this->input->Post('password');
        $re_password = $this->input->Post('re_password');

       
        // Input validation
        if (empty($username) || empty($password) || empty($re_password) || empty($name)) {
            $this->session->set_flashdata('message', 'All fields are required!');
            redirect('Signup');
        }

        if ($password !== $re_password) {
            $this->session->set_flashdata('message', 'Password do not match!');
            redirect('Signup');
        }

         // Check if username is already taken
         $user = $this->SignupModel->getUserByUsername($username);
         if ($user) {
             $this->session->set_flashdata('message', 'Username is already taken!');
         }
        else{
            $this->SignupModel-> submit_btn($name,  $password, $username);
            $this->session->set_flashdata('message', 'You have Successfully registred!');
        }

        redirect('Auth');

    }

  
}
