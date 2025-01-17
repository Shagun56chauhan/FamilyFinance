<?php 

class TotalExpenseModel extends CI_Model
{

    public function __construct() {
        $this->load->database();
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
    

// Fetch weekly grouped data for the last 7 days, including today
public function getWeeklyExpenses($user_id) {
    // Calculate the date for 7 days ago from today
    $sevenDaysAgo = date('Y-m-d', strtotime('-6 days'));  // 6 days ago, so 7 days from today

    // Query to fetch expenses from today to 7 days ago
    $query = $this->db->query("
        SELECT 
            SUM(amount) as total_amount,
            DATE(created_at) as expense_date
        FROM expense
        WHERE user_id = ? 
        AND DATE(created_at) BETWEEN ? AND ?
        GROUP BY DATE(created_at)
        ORDER BY DATE(created_at) ASC
    ", [$user_id, $sevenDaysAgo, date('Y-m-d')]);

    return $query->result_array();
}


  // Fetch daily expenses for multiple months
  public function getMonthlyExpenses($user_id, $startDate, $endDate) {
    $query = $this->db->query("
        SELECT 
            amount,type,
            DATE_FORMAT(created_at, '%Y-%m-%d') as date
        FROM expense
        WHERE user_id = ? 
        AND DATE(created_at) BETWEEN ? AND ?
        ORDER BY created_at ASC
    ", [$user_id, $startDate, $endDate]);
    return $query->result_array();
}



// pie chart
public function get_types( $user_id) {
    // Query to fetch the expense type and the created_at (date of expense)
    $this->db->select('type, SUM(amount) as amount');
    $this->db->where('user_id', $user_id);
    $this->db->group_by('type');
    $this->db->order_by('type'); // Order by type for clarity
    $query = $this->db->get('expense'); // Assuming 'expenses' is your table name

    return $query->result_array();
}

// pie chart



 // Function to get expenses by selected month
 public function get_expenses_by_year_month($year, $month, $user_id)
{
    // Fetch aggregated expenses by type for the given year and month
    $query = $this->db->select('type, SUM(amount) as amount')
                      ->from('expense')
                      ->where('user_id', $user_id) // Filter by user ID
                      ->where('YEAR(created_at)', $year) // Filter by year
                      ->where('MONTH(created_at)', $month) // Filter by month
                      ->group_by('type') // Group by expense type
                      ->get();
    
    return $query->result_array(); // Return result as an array
}




//  second pie


public function get_pietypes($user_id) {
    // Get current month and year
    $currentMonth = date('m');
    $currentYear = date('Y');

    // Query to fetch the expense type, amount, and filter by current month and year
    $this->db->select('type, SUM(amount) as amount');
    $this->db->where('user_id', $user_id);
    $this->db->where('MONTH(created_at)', $currentMonth); // Filter by current month
    $this->db->where('YEAR(created_at)', $currentYear); // Filter by current year
    $this->db->group_by('type');
    $this->db->order_by('type'); // Order by type for clarity
    $query = $this->db->get('expense'); // Assuming 'expenses' is your table name

    return $query->result_array();
}



// second pie
}



































