<?php 

class ViewRecordModel extends CI_Model
{

    public function __construct() {
        $this->load->database();
    }

    function getUsers($user_id)
    {
       
        $query = $this->db->get_where('vehicle', array('user_id' => $user_id));
        return $query->result_array();  // Use row_array() for a single user 
    }



               
//    edit
// Get expense by ID
public function getExpenseById($id) {
    $query = $this->db->get_where('vehicle', ['id' => $id]);
    return $query->result_array();  // Return the expense as an associative array
}

// Update expense by ID
public function updateExpense($id, $data) {
    $this->db->where('id', $id);
    return $this->db->update('vehicle', $data);  // Update the expense record
}




public function deleteExpense($id) {
    return $this->db->delete('vehicle', ['id' => $id]);
}
  
}




