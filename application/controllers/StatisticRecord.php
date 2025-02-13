<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StatisticRecord extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
          // Check if the user is logged in before accessing the page
          if (!$this->session->userdata('user_id')) {
            redirect('Auth'); // Redirect to login page if not logged in
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
  // Get selected year and month from the dropdown
  $selected_year = !empty($this->input->get('year')) ? $this->input->get('year') : date('Y');

  // Ensure the month is always two digits (e.g., '02' instead of '2')
  $selected_month = !empty($this->input->get('month')) ? str_pad($this->input->get('month'), 2, '0', STR_PAD_LEFT) : date('m');
// Fetch vehicle log data based on selected year and month
$data['month_types'] = ($selected_year && $selected_month && $user_id) ? 
    $this->StatisticRecordModel->get_vehicle_data_by_month_year($selected_year, $selected_month, $user_id) : [];

$data['selected_year'] = $selected_year;
$data['selected_month'] = $selected_month;

// List of month names for the dropdown
$data['months'] = [
    1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
    5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
    9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December',
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

 // Fetch the vehicle records for the current month
 $monthlyRecords = $this->StatisticRecordModel->getMonthlyVehicleRecords($user_id);
 $data['monthly_records'] = $this->prepareMonthlyData($monthlyRecords);

 // Fetch the vehicle records for the current year
 $yearlyRecords = $this->StatisticRecordModel->getYearlyVehicleRecords($user_id);
 $data['yearly_records'] = $this->prepareYearlyData($yearlyRecords);

 // Add the current month and year data for display
 $data['currentMonth'] = date('F Y');  // Current month
 $data['currentYear'] = date('Y');     // Current year
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




// yearly line chart


// Prepare data for the last 12 months
private function prepareYearlyData($yearlyRecords) {
    $yearlyData = [
        'labels' => [],  // Store all months of the current year
        'distance' => []  // Store calculated distances for each month
    ];

    // Get the current year
    $currentYear = date('Y');  // Current year
    $months = [
        '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr',
        '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug',
        '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec'
    ];

    // Initialize all months with 0 distance
    foreach ($months as $monthNum => $monthName) {
        $yearlyData['labels'][] = $monthName;
        $yearlyData['distance'][] = 0;  // Initialize distance for each month to 0
    }

    // Track previous readings for each vehicle type and month
    $previousReadingsByTypeAndMonth = [];

    foreach ($yearlyRecords as $record) {
        $recordDate = $record['record_date'];
        $vehicleType = $record['vehicle_type'];
        $currentReading = (float) $record['reading'];

        // Extract the month and year from the record date
        $year = date('Y', strtotime($recordDate));
        $month = date('m', strtotime($recordDate));

        // Make sure it's for the current year
        if ($year == $currentYear) {
            $monthIndex = array_search($month, array_keys($months));  // Find the index of the month
            if ($monthIndex !== false) {
                // Initialize previous reading for this vehicle type if not set
                if (!isset($previousReadingsByTypeAndMonth[$vehicleType][$month])) {
                    $previousReadingsByTypeAndMonth[$vehicleType][$month] = null;
                }

                // Calculate distance for the same vehicle type and month
                if ($previousReadingsByTypeAndMonth[$vehicleType][$month] !== null && $currentReading >= $previousReadingsByTypeAndMonth[$vehicleType][$month]) {
                    $distance = $currentReading - $previousReadingsByTypeAndMonth[$vehicleType][$month];
                    $yearlyData['distance'][$monthIndex] += $distance; // Add distance to the correct month's total
                }

                // Update the previous reading for this vehicle type and month
                $previousReadingsByTypeAndMonth[$vehicleType][$month] = $currentReading;
            }
        }
    }

    return $yearlyData;
}






// yearly line chart





// monthly table
public function getMonthlyDistances()
{
    $user_id = $this->session->userdata('user_id');
    $selected_year = $this->input->get('year');
    $selected_month = $this->input->get('month');

    // Fetch distance data if year and month are selected, otherwise set to an empty array
    $month_types = ($selected_year && $selected_month && $user_id)
        ? $this->StatisticRecordModel->get_vehicle_data_by_month_year($selected_year, $selected_month, $user_id)
        : [];

    $grandTotalDistance = 0;
    ob_start();
    if (!empty($month_types)) {
        echo '<div class="user">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Vehicle Type</th>
                        <th>Total Distance (km)</th>
                    </tr>
                </thead>
                <tbody>';
        foreach ($month_types as $record) {
            $grandTotalDistance += $record['total_distance'];
            echo '<tr>
                    <td>' . htmlspecialchars($record['type']) . '</td>
                    <td>' . number_format($record['total_distance'], 2) . ' km</td>
                </tr>';
        }
        echo '</tbody>
                <tfoot>
                    <tr>
                        <th>Total Distance</th>
                        <th>' . number_format($grandTotalDistance, 2) . ' km</th>
                    </tr>
                </tfoot>
            </table>
        </div>';
    } else {
        echo '<p style="text-align: center;">No distances found for the selected period.</p>';
    }
    $html = ob_get_clean();
    echo $html;
}
// monthly table


}