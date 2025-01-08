<?php 

class ViewExpenseModel extends CI_Model
{

    public function __construct() {
        $this->load->database();
    }

    function getUsers($user_id)
    {
       
        $query = $this->db->get_where('expense', array('user_id' => $user_id));
        return $query->result_array();  // Use row_array() for a single user 
    }
}























































