<?php 

class SetLimitModel extends CI_Model
{

    public function __construct() {
        $this->load->database();
    }

    function insertUser($data)
    {
        $this->db->insert("expense", $data);
        if ($this->db->affected_rows() >= 0){
            return true; 
        } else {
            return false;   
        }
    }
  

    public function submit_btn($user_id, $type, $set_limit, $date) {
        // Ensure $set_limit is not null
    if ($set_limit === null) {
        $set_limit = 0;  // Default value
    }
        $data = array(
            'user_id' => $user_id,  // Add user_id here
            'type' => $type,
            'set_limit' => $set_limit,
            'created_at' => $date,
          
           
        );
      
        return $this->db->insert('expense', $data);

    }
 // Method to get unique expense types for a user
 function getUniqueExpenseTypes() {
    $this->db->select('type');
    $this->db->distinct();
   
    $query = $this->db->get('admin_expense');
    return $query->result_array();
}
   


  


}