<?php 

class AuthModel extends CI_Model
{

    public function __construct() {
        $this->load->database();
    }

    function getUsers()
    {
       $query = $this->db->get('login');
       return $query->result_array();
    }


      // Check if a user exists by name
     public function getUserByName($name) {
        $query = $this->db->get_where('login', array('name' => $name));
        return $query->row_array();
    }

  
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth'); // Redirect to login page
    }

    
    
  


}