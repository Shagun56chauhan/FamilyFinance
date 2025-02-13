<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TotalExpense extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        // Check if the user is logged in before accessing the page
        if (!$this->session->userdata('user_id')) {
            redirect('Auth'); // Redirect to login page if not logged in
        }
        $this->load->model("TotalExpenseModel");
        $this->load->library('session');
    }

    public function index()
    {
        // Get user ID from session
        $user_id = $this->session->userdata('user_id');





        // table for per month expense
        // Get selected month from the dropdown
        // Get selected year and month from the dropdown
        // Get selected year and month from the dropdown
   // Get selected year and month from the dropdown
$selected_year = !empty($this->input->get('year')) ? $this->input->get('year') : date('Y');

// Ensure the month is always two digits (e.g., '02' instead of '2')
$selected_month = !empty($this->input->get('month')) ? str_pad($this->input->get('month'), 2, '0', STR_PAD_LEFT) : date('m');



        // Fetch expense data if year and month are selected, otherwise set to an empty array
        $data['month_types'] = ($selected_year && $selected_month && $user_id)
            ? $this->TotalExpenseModel->get_expenses_by_year_month($selected_year, $selected_month, $user_id)
            : [];

        $data['selected_year'] = $selected_year;
        $data['selected_month'] = $selected_month;

        // List of month names for the dropdown
        $data['months'] = [
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December'
        ];

        //    table for per monh expense



        // Calculate total expense for all expenses (not filtered by type)
        $data['total_expense'] = $this->TotalExpenseModel->calculateTotalExpense($user_id); // Pass null for all expenses


        //  chart

        // Fetch expenses for the past 7 days (including today)
        $weeklyExpenses = $this->TotalExpenseModel->getWeeklyExpenses($user_id);

        // Prepare the data for the chart
        $data['weekly_expenses'] = $this->prepareWeeklyData($weeklyExpenses);



        // chart

        // pie

        $data['types'] = $this->TotalExpenseModel->get_types($user_id);

        // pie

        // table

        $data['table_types'] = $this->TotalExpenseModel->get_monthly_types($user_id);
        // table

        // second pie
        $data['pietypes'] = $this->TotalExpenseModel->get_pietypes($user_id);


        // second pie

        // line chart

       

        // Get data for the monthly chart
    $monthlyExpenses = $this->TotalExpenseModel->getExpenses($user_id, 'monthly');
    $data['monthly_expenses'] = $this->prepareChartData($monthlyExpenses, 'monthly');

    // Get data for the yearly chart
    $yearlyExpenses = $this->TotalExpenseModel->getExpenses($user_id, 'yearly');
    $data['yearly_expenses'] = $this->prepareChartData($yearlyExpenses, 'yearly');


        

        // line chart

        $data['currentMonth'] = date('F Y');  // e.g., "November 2024"

        // Load the view with the fetched data
        $this->load->view("totalexpense", $data);
    }


    // bar
// Helper function to structure data for the last 7 days for Chart.js
    private function prepareWeeklyData($weeklyExpenses)
    {
        $weeklyData = [
            'labels' => [],  // Store the 7 days labels
            'amounts' => []  // Store the summed amounts for each day
        ];

        // Loop through the results and set up the labels and amounts
        $today = date('Y-m-d');
        $dateLabels = []; // To create the list of dates from today to 7 days ago
        for ($i = 6; $i >= 0; $i--) {
            $dateLabels[] = date('Y-m-d', strtotime("$today -$i days"));
        }

        foreach ($dateLabels as $label) {
            $weeklyData['labels'][] = $label;
            $weeklyData['amounts'][] = 0; // Default amount for each day to 0
        }

        // Fill in the data from the query result
        foreach ($weeklyExpenses as $expense) {
            $key = array_search($expense['expense_date'], $weeklyData['labels']);
            if ($key !== false) {
                $weeklyData['amounts'][$key] = $expense['total_amount'];
            }
        }

        return $weeklyData;
    }
    // bar


    // Helper function to structure monthly data for Chart.js
   // Function to prepare data for charts
   private function prepareChartData($expenses, $type) {
    $chartData = [
        'labels' => [],
        'amounts' => []
    ];

    if ($type === 'monthly') {
        $daysInMonth = date('t');
        $currentMonth = date('Y-m');

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = sprintf("%02d", $day); // Format: 01, 02, 03...
            $chartData['labels'][] = $date;
            $chartData['amounts'][$date] = 0;
        }
    } elseif ($type === 'yearly') {
        $months = [
            "01" => "Jan", "02" => "Feb", "03" => "Mar", "04" => "Apr",
            "05" => "May", "06" => "Jun", "07" => "Jul", "08" => "Aug",
            "09" => "Sep", "10" => "Oct", "11" => "Nov", "12" => "Dec"
        ];

        foreach ($months as $num => $monthName) {
            $chartData['labels'][] = $monthName;
            $chartData['amounts'][$num] = 0;
        }
    }

    foreach ($expenses as $expense) {
        if ($type === 'monthly') {
            $date = date('d', strtotime($expense['date'])); // Extract day only
        } else {
            $date = date('m', strtotime($expense['date'])); // Extract month number
        }

        $amount = (float) $expense['amount'];

        if (isset($chartData['amounts'][$date])) {
            $chartData['amounts'][$date] += $amount;
        }
    }

    $chartData['amounts'] = array_values($chartData['amounts']);
    return $chartData;
}



// monthly table
public function getMonthlyExpenses()
{
    $user_id = $this->session->userdata('user_id');
    $selected_year = $this->input->get('year');
    $selected_month = $this->input->get('month');

    // Fetch expense data if year and month are selected, otherwise set to an empty array
    $month_types = ($selected_year && $selected_month && $user_id)
        ? $this->TotalExpenseModel->get_expenses_by_year_month($selected_year, $selected_month, $user_id)
        : [];

    $totalAmount = 0;
    ob_start();
    if (!empty($month_types)) {
        echo '<div class="user">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Expense Type</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>';
        foreach ($month_types as $expense) {
            $totalAmount += $expense['amount'];
            echo '<tr>
                    <td>' . htmlspecialchars($expense['type']) . '</td>
                    <td>' . number_format($expense['amount'], 2) . '</td>
                </tr>';
        }
        echo '</tbody>
                <tfoot>
                    <tr>
                        <th style="font-size:20px;">TOTAL</th>
                        <th style="font-size:20px;">' . number_format($totalAmount, 2) . '</th>
                    </tr>
                </tfoot>
            </table>
        </div>';
    } else {
        echo '<p style="text-align: center;">No expenses found for the selected period.</p>';
    }
    $html = ob_get_clean();
    echo $html;
}

// monthly table


}