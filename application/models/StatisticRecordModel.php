<?php 

class StatisticRecordModel extends CI_Model
{

    public function __construct() {
        $this->load->database();
    }
  function insertUser($data)
    {
        $this->db->insert("vehicle", $data);
        if ($this->db->affected_rows() >= 0) {
            return true;
        } else {
            return false;
        }
    }
  



// bar


// Fetch weekly grouped data for the last 7 days, including today
public function getWeeklyRecord($user_id)
{
    // Fetch records for the user, ordered by date
    $query = $this->db->select('type as vehicle_type, reading, DATE(created_at) as record_date')
                      ->from('vehicle')
                      ->where('user_id', $user_id) // Filter by user ID
                      ->order_by('created_at', 'ASC')
                      ->get();

    return $query->result_array();
}

// bar



// pie
// Function to calculate the total distance for each vehicle type for a user
public function getTotalDistanceByType($user_id) {
    $this->db->select('type, reading');
    $this->db->from('vehicle');
    $this->db->where('user_id', $user_id);
    $this->db->order_by('created_at ASC'); // Order by creation date
    $query = $this->db->get();
    $readings = $query->result_array();

    $total_distances = [];
    
    // Group readings by vehicle type
    $grouped_readings = [];
    foreach ($readings as $record) {
        $type = $record['type'];
        $reading = floatval($record['reading']);
        if (!isset($grouped_readings[$type])) {
            $grouped_readings[$type] = [];
        }
        $grouped_readings[$type][] = $reading;
    }

    // Calculate total distance for each vehicle type
    foreach ($grouped_readings as $type => $type_readings) {
        $total_distance = 0;

        // Iterate over readings to calculate total distance
        for ($i = 1; $i < count($type_readings); $i++) {
            $distance = $type_readings[$i] - $type_readings[$i - 1];
            if ($distance > 0) { // Only consider positive distances
                $total_distance += $distance;
            }
        }

        // Store total distance for the current vehicle type
        $total_distances[$type] = $total_distance;
    }

    // Sort total distances in descending order (largest distance first)
    arsort($total_distances);

    return $total_distances; // Return sorted total distances by distance traveled
}


// pie


// monthlypie



public function getTotalDistanceForCurrentMonth($user_id, $selected_year, $selected_month) {
    // Fetch all records for the user, ordered by date
    $query = $this->db->select('type, reading, DATE(created_at) as record_date')
                      ->from('vehicle')
                      ->where('user_id', $user_id) // Filter by user ID
                      ->order_by('created_at', 'ASC')
                      ->get();

    $records = $query->result_array();

    $current_month_distances = [];
    $previousReadingsByType = [];

    foreach ($records as $record) {
        $recordDate = $record['record_date'];
        $vehicleType = $record['type'];
        $currentReading = (float) $record['reading'];

        // Extract the month and year from the record date
        $year = date('Y', strtotime($recordDate));
        $month = date('m', strtotime($recordDate));

        // Calculate distance for the same vehicle type from the beginning
        if (!isset($previousReadingsByType[$vehicleType])) {
            $previousReadingsByType[$vehicleType] = $currentReading;
        } else {
            $distance = $currentReading - $previousReadingsByType[$vehicleType];
            $previousReadingsByType[$vehicleType] = $currentReading;

            // Add distance to the correct vehicle type's total if it's the selected month and year
            if ($year == $selected_year && $month == $selected_month) {
                if (!isset($current_month_distances[$vehicleType])) {
                    $current_month_distances[$vehicleType] = 0;
                }
                $current_month_distances[$vehicleType] += $distance;
            }
        }
    }

    // Sort distances in descending order (optional)
    arsort($current_month_distances);

    return $current_month_distances; // Return distances for the current month
}

// monthlypie





// bar


 // Fetch total distance for all vehicle types on a specific date for a user
 public function calculateTotalRecord($user_id)
 {
     $this->db->select('type, reading, DATE(created_at) as record_date');
     $this->db->from('vehicle');
     $this->db->where('user_id', $user_id);
     $this->db->order_by('created_at ASC');
     $query = $this->db->get();
     $readings = $query->result_array();

     // Calculate total distance for each vehicle type for a specific date
     $total_distances = [];
     $grouped_readings = [];

     // Group readings by type
     foreach ($readings as $record) {
         $type = $record['type'];
         $reading = floatval($record['reading']);
         $date = $record['record_date'];

         if (!isset($grouped_readings[$type][$date])) {
             $grouped_readings[$type][$date] = [];
         }
         $grouped_readings[$type][$date][] = $reading;
     }

     // Calculate total distance for each vehicle type for the selected date
     foreach ($grouped_readings as $type => $dates) {
         foreach ($dates as $date => $readings) {
             $total_distance = 0;
             $previous_reading = null;
             
             // Calculate distance between consecutive readings
             foreach ($readings as $reading) {
                 if ($previous_reading !== null && $reading >= $previous_reading) {
                     $distance = $reading - $previous_reading;
                     $total_distance += $distance;
                 }
                 $previous_reading = $reading;
             }

             // Store total distance for this type and date
             $total_distances[$type] = $total_distance;
         }
     }

     return $total_distances; // Return the calculated distances by type
 }



// bar



// line chart

public function getMonthlyVehicleRecords($user_id)
{
    // Fetch records for the user, ordered by date
    $query = $this->db->select('type as vehicle_type, reading, DATE(created_at) as record_date')
                      ->from('vehicle')
                      ->where('user_id', $user_id) // Filter by user ID
                      ->order_by('created_at', 'ASC')
                      ->get();

    return $query->result_array();
}

public function getYearlyVehicleRecords($user_id)
{
    // Fetch records for the user, ordered by date
    $query = $this->db->select('type as vehicle_type, reading, DATE(created_at) as record_date')
                      ->from('vehicle')
                      ->where('user_id', $user_id) // Filter by user ID
                      ->order_by('created_at', 'ASC')
                      ->get();

    return $query->result_array();
}








// line chart


 // Function to get expenses by selected month

public function get_vehicle_data_by_month_year($selected_year, $selected_month, $user_id)
{
    // Fetch all records for the user, ordered by date
    $query = $this->db->select('type, reading, DATE(created_at) as record_date')
                      ->from('vehicle')
                      ->where('user_id', $user_id) // Filter by user ID
                      ->order_by('created_at', 'ASC')
                      ->get();

    $records = $query->result_array();

    $current_month_distances = [];
    $previousReadingsByType = [];

    foreach ($records as $record) {
        $recordDate = $record['record_date'];
        $vehicleType = $record['type'];
        $currentReading = (float) $record['reading'];

        // Extract the month and year from the record date
        $year = date('Y', strtotime($recordDate));
        $month = date('m', strtotime($recordDate));

        // Calculate distance for the same vehicle type from the beginning
        if (!isset($previousReadingsByType[$vehicleType])) {
            $previousReadingsByType[$vehicleType] = $currentReading;
        } else {
            $distance = $currentReading - $previousReadingsByType[$vehicleType];
            $previousReadingsByType[$vehicleType] = $currentReading;

            // Add distance to the correct vehicle type's total if it's the selected month and year
            if ($year == $selected_year && $month == $selected_month) {
                if (!isset($current_month_distances[$vehicleType])) {
                    $current_month_distances[$vehicleType] = 0;
                }
                $current_month_distances[$vehicleType] += $distance;
            }
        }
    }

    // Prepare the data in the required format
    $result = [];
    foreach ($current_month_distances as $type => $total_distance) {
        $result[] = [
            'type' => $type,
            'total_distance' => $total_distance
        ];
    }

    return $result; // Return distances for the current month
}




}




































































