<?php 

class ViewExpenseModel extends CI_Model
{

    public function __construct() {
        $this->load->database();
    }

   


     // Function to fetch expenses for the user, optionally filtered by expense type
     function getUsers($user_id, $type) {
        
        $this->db->where('user_id', $user_id);
      
        if ($type) {
            $this->db->where('type', $type); // Filter by selected expense type
        }
        $query = $this->db->get('expense');
           
      
        return $query->result_array();
    }

    
      // Method to get unique expense types for a user
      function getUniqueExpenseTypes($user_id) {
        $this->db->select('type');
        $this->db->distinct();
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('admin_expense');
        return $query->result_array();
    }

       // Method to calculate the total expense for a user, optionally filtered by expense type
function calculateTotalExpense($user_id, $type = null) {
    $this->db->select_sum('amount'); // Sum the 'amount' column
    $this->db->where('user_id', $user_id);

    if ($type) {
        $this->db->where('type', $type); // Apply type filter if provided
    }

    $query = $this->db->get('expense');
    $result = $query->row_array();
    
    // Return the total amount, or 0 if no result found
    return isset($result['amount']) ? $result['amount'] : 0;
}
}























































