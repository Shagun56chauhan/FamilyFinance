<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TotalExpense extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model("TotalExpenseModel");
        $this->load->library('session');
    }

    public function index()
    {
        // Get user ID from session
        $user_id = $this->session->userdata('user_id');

        // Fetch all expenses and unique expense types for the user
        $data['view'] = [];
        $data['total_expense'] = 0; // Initialize total expense

        $data['expense_types'] = $this->TotalExpenseModel->getUniqueExpenseTypes($user_id);



        // table for per month expense
        // Get selected month from the dropdown
        $selected_month = $this->input->get('month');

        // If the selected month is in the format 'YYYY-MM', extract the month number
        if ($selected_month) {
            $selected_month = date('m', strtotime($selected_month)); // Extract the month number
        }


        // Fetch expense data if a month is selected, otherwise set to an empty array
        $data['month_types'] = $selected_month ? $this->TotalExpenseModel->get_expenses_by_month($selected_month) : [];
        
        $data['selected_month'] = $selected_month;
        
         // List of month names for the dropdown
    $data['months'] = [
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December',
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

        // Get the first and last date of the current month
        $startDate = date('Y-m-01'); // First day of the current month
        $endDate = date('Y-m-t'); // Last day of the current month

        // Get monthly expenses for the current month
        $monthlyExpenses = $this->TotalExpenseModel->getMonthlyExpenses($user_id, $startDate, $endDate);

        // Prepare the monthly data for the chart
        $data['monthly_expenses'] = $this->prepareMonthlyData($monthlyExpenses);


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
    private function prepareMonthlyData($monthlyExpenses)
    {
        $monthlyData = [
            'labels' => [],  // Store all dates of the current month
            'amounts' => []  // Store total amounts for each date, defaulting to zero
        ];

        $currentMonth = date('Y-m'); // Get the current month in "YYYY-MM" format
        $daysInMonth = date('t');    // Total days in the current month

        // Generate all dates for the current month
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = sprintf("%s-%02d", $currentMonth, $day); // Format: "YYYY-MM-DD"
            $monthlyData['labels'][] = $date;
            $monthlyData['amounts'][$date] = 0; // Initialize each date's amount to zero
        }

        // Accumulate the amounts from the provided expenses
        foreach ($monthlyExpenses as $expense) {
            $date = $expense['date'];  // Example: "2024-11-01"
            $amount = (float) $expense['amount'];

            // Sum the amounts for the matching date
            if (isset($monthlyData['amounts'][$date])) {
                $monthlyData['amounts'][$date] += $amount;
            }
        }

        // Convert the associative 'amounts' array to a sequential array for the chart
        $monthlyData['amounts'] = array_values($monthlyData['amounts']);

        return $monthlyData;
    }

    function fetchtype()
    {
        // Get user ID from session
        $user_id = $this->session->userdata('user_id');
        $selected_type = $this->input->post('type');
        $data['expense_types'] = $this->TotalExpenseModel->getUniqueExpenseTypes($user_id);


        $data['view'] = $this->TotalExpenseModel->getUsers($user_id, $selected_type); // Fetch filtered data

        // Calculate total expense for all expenses (not filtered by type)
        $data['total_expense'] = $this->TotalExpenseModel->calculateTotalExpense($user_id);

        // Calculate total expense for the selected type
        $data['selected_type_expense'] = $this->TotalExpenseModel->calculateTotalExpense($user_id, $selected_type);

        // Pass the selected type to the view
        $data['selected_type'] = $selected_type;

        // Load the view with the fetched data
        $this->load->view("totalexpense", $data);
    }
}