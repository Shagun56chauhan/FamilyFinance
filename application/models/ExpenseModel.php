<?php 

class ExpenseModel extends CI_Model
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
  

    public function submit_btn($user_id, $type, $amount, $date, $remarks) {
        $data = array(
            'user_id' => $user_id,  // Add user_id here
            'type' => $type,
            'amount' => $amount,
            'created_at' => $date,
            'remarks' => $remarks,
           
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
   
//    edit
// Get expense by ID
public function getExpenseById($id) {
    $query = $this->db->get_where('expense', ['id' => $id]);
    return $query->result_array();  // Return the expense as an associative array
}

// Update expense by ID
public function updateExpense($id, $data) {
    $this->db->where('id', $id);
    return $this->db->update('expense', $data);  // Update the expense record
}




public function deleteExpense($id) {
    return $this->db->delete('expense', ['id' => $id]);
}
  


}