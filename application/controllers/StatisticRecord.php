<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StatisticRecord extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
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

        $data['record_types'] = $this->StatisticRecordModel->getVehicleTypes($user_id); // Keep the vehicle types for the dropdown

            
    
// Calculate total expense for all expenses (not filtered by type)
$data['total_record'] = $this->StatisticRecordModel->calculateTotalRecord($user_id); // Pass null for all expenses



      

        

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


// line chart

// Get the first and last date of the current month
// $startDate = date('Y-m-01'); // First day of the current month
// $endDate = date('Y-m-t'); // Last day of the current month

// Get monthly expenses for the current month
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
        'labels' => [], // Store the 7 days labels
        'distance' => [] // Store the total distance for each vehicle type for the last 7 days
    ];

    // Create labels for the last 7 days
    $today = date('Y-m-d');
    $dateLabels = [];
    for ($i = 6; $i >= 0; $i--) {
        $dateLabels[] = date('Y-m-d', strtotime("$today -$i days"));
    }

    $weeklyData['labels'] = $dateLabels;

    // Initialize distances array with zeros for each date and vehicle type
    $distanceByDateAndType = [];
    foreach ($dateLabels as $date) {
        $distanceByDateAndType[$date] = [];
    }

    // Populate distances based on weeklyRecord
    foreach ($weeklyRecord as $record) {
        $date = $record['record_date'];
        $vehicleType = $record['vehicle_type'];
        $reading = $record['reading'];

        if (!isset($distanceByDateAndType[$date][$vehicleType])) {
            $distanceByDateAndType[$date][$vehicleType] = 0;
        }

        $distanceByDateAndType[$date][$vehicleType] += $reading;
    }

    // Structure data for the chart
    foreach ($dateLabels as $date) {
        foreach ($distanceByDateAndType[$date] as $vehicleType => $totalDistance) {
            if (!isset($weeklyData['distance'][$vehicleType])) {
                $weeklyData['distance'][$vehicleType] = [];
            }
            $weeklyData['distance'][$vehicleType][] = $totalDistance;
        }

        // Ensure all vehicle types have data for every day
        foreach (array_keys($distanceByDateAndType) as $vehicleType) {
            if (!isset($weeklyData['distance'][$vehicleType])) {
                $weeklyData['distance'][$vehicleType] = array_fill(0, 7, 0);
            }
        }
    }

   
    return $weeklyData;
}



// hlooooooooooooooooooooooooooooooooooo












// bar



    public function show_vehicle_distance()
    {
        $type = $this->input->get('type');  // Get selected type from the form
        $user_id = $this->session->userdata('user_id');

        // Calculate the total distance for the selected vehicle type and user
        $data['total_distance'] = $this->StatisticRecordModel->getTotalDistance($type, $user_id);


    // Fetch filtered records based on the selected type and user ID
    $data['records'] = $this->StatisticRecordModel->getFilteredRecords($type, $user_id);
  
    
        $this->load->view('statisticrecord', $data);

    }


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

    // Calculate distances using the monthly records
    $previousReading = null;
    foreach ($monthlyRecords as $record) {
        $date = $record['record_date'];  // Example: "2024-11-05"
        $reading = (float)$record['reading'];

        // Find the index of the current record date in the labels array
        $key = array_search($date, $monthlyData['labels']);
        if ($key !== false) {
            // Calculate the distance if the previous reading exists
            if ($previousReading !== null && $reading >= $previousReading) {
                $distance = $reading - $previousReading;
                $monthlyData['distance'][$key] += $distance;
            }
        }

        // Update previous reading for next calculation
        $previousReading = $reading;
    }

    return $monthlyData;
}





// line chart




}