<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StatisticRecord extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
          // Check if the user is logged in before accessing the page
          if (!$this->session->userdata('user_id')) {
            redirect('auth'); // Redirect to login page if not logged in
        }
        $this->load->model("StatisticRecordModel");
        $this->load->library('session');
    }

    public function index()
    {
        // Get user ID from session
        $user_id = $this->session->userdata('user_id');
        $type = $this->input->get('type');  // Get selected type from the form

         // Fetch all expenses and unique expense types for the user
         $data['view'] = [];
         $data['total_record'] = 0; // Initialize total expense

       

            
    


// monthly table


// Get selected month from the dropdown
$selected_month = $this->input->get('month');

// If the selected month is in the format 'YYYY-MM', extract the month number
if ($selected_month) {
    $selected_month = date('m', strtotime($selected_month)); // Extract the month number
}


// Fetch expense data if a month is selected, otherwise set to an empty array
$data['month_types'] = $selected_month ? $this->StatisticRecordModel->get_expenses_by_month($selected_month) : [];

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



// monthly table
      

        

 // bar chart
   
  // Fetch expenses for the past 7 days (including today)
  $weeklyRecord = $this->StatisticRecordModel->getWeeklyRecord( $user_id);

     
  // Prepare the data for the chart
  $data['weekly_records'] = $this->prepareWeeklyData($weeklyRecord);
  
//   print_r($data['weekly_records']);
//   die();
   
 // bar chart


// pie

    // Get total distances for each vehicle type
    $data['total_distances_by_type'] = $this->StatisticRecordModel->getTotalDistanceByType($user_id);

    // Preparing data for the pie chart
    $types = [];
    $distances = [];

    foreach ($data['total_distances_by_type'] as $type => $distance) {
        $types[] = $type;
        $distances[] = $distance;
    }

    // Pass the data to the view
    $data['types'] = $types;
    $data['distances'] = $distances;


// pie


// monthlypie


$data['current_month_distances_by_type'] = $this->StatisticRecordModel->getTotalDistanceForCurrentMonth($user_id);

// Prepare data for the current month pie chart
$current_month_types = [];
$current_month_distances = [];

foreach ($data['current_month_distances_by_type'] as $type => $distance) {
    $current_month_types[] = $type; // Vehicle type
    $current_month_distances[] = $distance; // Distance for the type
}

// Pass the data to the view
$data['current_month_types'] = $current_month_types;
$data['current_month_distances'] = $current_month_distances;



// monthlypie


// line chart

// Get the first and last date of the current month
// $startDate = date('Y-m-01'); // First day of the current month
// $endDate = date('Y-m-t'); // Last day of the current month

// Get monthly record for the current month
$monthlyRecords = $this->StatisticRecordModel->getMonthlyVehicleRecords($user_id);

// Prepare the monthly data for the chart
$data['monthly_records'] = $this->prepareMonthlyData($monthlyRecords);

$data['currentMonth'] = date('F Y');  // e.g., "November 2024"
// line chart

         $this->load->view("statisticrecord", $data);
    }


       
// bar
// Helper function to structure data for the last 7 days for Chart.js
// Helper function to structure data for the last 7 days for Chart.js
private function prepareWeeklyData($weeklyRecord) {
    $weeklyData = [
        'labels' => [],  // Store the 7 days labels
        'distance' => []  // Store the summed distances for each day
    ];

    // Generate labels for the last 7 days
    $today = date('Y-m-d');
    $dateLabels = [];
    for ($i = 6; $i >= 0; $i--) {
        $dateLabels[] = date('Y-m-d', strtotime("$today -$i days"));
    }

    foreach ($dateLabels as $label) {
        $weeklyData['labels'][] = $label;
        $weeklyData['distance'][] = 0; // Default distance for each day to 0
    }

    // Track previous readings for each vehicle type
    $previousReadingsByType = [];

    foreach ($weeklyRecord as $record) {
        $recordDate = $record['record_date'];
        $vehicleType = $record['vehicle_type'];
        $currentReading = (float) $record['reading'];

        // Find the correct key for the record's date
        $key = array_search($recordDate, $weeklyData['labels']);
        if ($key !== false) {
            // Initialize previous reading for this vehicle type if not set
            if (!isset($previousReadingsByType[$vehicleType])) {
                $previousReadingsByType[$vehicleType] = null;
            }

            // Calculate distance for the same vehicle type
            if ($previousReadingsByType[$vehicleType] !== null && $currentReading >= $previousReadingsByType[$vehicleType]) {
                $distance = $currentReading - $previousReadingsByType[$vehicleType];
                $weeklyData['distance'][$key] += $distance; // Add distance to the correct day's total
            }

            // Update the previous reading for this vehicle type
            $previousReadingsByType[$vehicleType] = $currentReading;
        }
    }

    return $weeklyData;
}














// bar





// line chart



// Prepare data for the last 12 months
private function prepareMonthlyData($monthlyRecords) {
    $monthlyData = [
        'labels' => [],  // Store all dates of the current month
        'distance' => []  // Store calculated distances for each date
    ];

    // Get the first and last date of the current month
    $currentMonthStart = date('Y-m-01');  // Start of the current month
    $currentMonthEnd = date('Y-m-t');     // End of the current month

    // Generate all dates for the current month and initialize with 0 distance
    $currentDate = $currentMonthStart;
    while ($currentDate <= $currentMonthEnd) {
        $monthlyData['labels'][] = $currentDate;
        $monthlyData['distance'][] = 0;  // Initialize distance for each date to 0
        $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
    }

   // Track previous readings for each vehicle type
   $previousReadingsByType = [];

    foreach ($monthlyRecords as $record) {
        $recordDate = $record['record_date'];
        $vehicleType = $record['vehicle_type'];
        $currentReading = (float) $record['reading'];

    // Find the correct key for the record's date
    $key = array_search($recordDate, $monthlyData['labels']);
    if ($key !== false) {
        // Initialize previous reading for this vehicle type if not set
        if (!isset($previousReadingsByType[$vehicleType])) {
            $previousReadingsByType[$vehicleType] = null;
        }

        // Calculate distance for the same vehicle type
        if ($previousReadingsByType[$vehicleType] !== null && $currentReading >= $previousReadingsByType[$vehicleType]) {
            $distance = $currentReading - $previousReadingsByType[$vehicleType];
            $monthlyData['distance'][$key] += $distance; // Add distance to the correct day's total
        }

        // Update the previous reading for this vehicle type
        $previousReadingsByType[$vehicleType] = $currentReading;
    }
}

    return $monthlyData;
}





// line chart




}