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
  

// Fetch records filtered by vehicle type and user_id
public function getFilteredRecords($type, $user_id)
{
    $this->db->where('type', $type);
    $this->db->where('user_id', $user_id);
    $query = $this->db->get('vehicle');
    return $query->result_array();
}
  


// dropdown
    // Get all vehicle types (if you want to list available types)
    public function getVehicleTypes($user_id)
    {
        $this->db->distinct();
        $this->db->select('type');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('admin_vehicle');
        return $query->result_array();
    }
    

// dropdown




// Calculate total distance for a specific vehicle type and user
public function getTotalDistance($type, $user_id)
{
    $this->db->select('reading');
    $this->db->from('vehicle');
    $this->db->where('type', $type);
    $this->db->where('user_id', $user_id);  // Ensure it's for the logged-in user
    $this->db->order_by('created_at', 'ASC');
    $query = $this->db->get();
    $readings = $query->result_array();

    $total_distance = 0;


    // Calculate total distance traveled, even for more than two readings
    if (count($readings) > 1) {
        $initial_reading = $readings[0]['reading']; // Take the first reading as the initial reading

        // Iterate through all readings to sum the difference
        for ($i = 1; $i < count($readings); $i++) {
            $current_reading = $readings[$i]['reading'];

            // Ensure readings are valid numbers and calculate the difference
            if (is_numeric($current_reading) && is_numeric($initial_reading)) {
                $distance_difference = $current_reading - $initial_reading;

                if ($distance_difference > 0) { // Ensure no negative values
                    $total_distance += $distance_difference;
                }

                // Update initial reading to current for the next iteration
                $initial_reading = $current_reading;
            }
        }
    }

    return $total_distance;
}






// bar


// Fetch weekly grouped data for the last 7 days, including today
public function getWeeklyRecord($user_id) {
    // Query to get vehicle readings for the last 7 days
    $this->db->select('reading, type as vehicle_type, DATE(created_at) as record_date');
    $this->db->from('vehicle');
    $this->db->where('user_id', $user_id);
    $this->db->where('DATE(created_at) >=', date('Y-m-d', strtotime('-6 days'))); // Get readings for the last 7 days
    $this->db->where('DATE(created_at) <=', date('Y-m-d')); // Up to today
    $this->db->order_by('record_date', 'ASC');
    $query = $this->db->get();

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

public function getMonthlyVehicleRecords($user_id) {
    $this->db->select('reading, DATE(created_at) as record_date');
    $this->db->from('vehicle');
    $this->db->where('user_id', $user_id);

    // Filter for the current month
    $currentMonthStart = date('Y-m-01');  // Start of the current month
    $currentMonthEnd = date('Y-m-t');  // End of the current month

    $this->db->where('DATE(created_at) >=', $currentMonthStart);
    $this->db->where('DATE(created_at) <=', $currentMonthEnd);
    $this->db->order_by('created_at', 'ASC');  // Ensure chronological order

    $query = $this->db->get();
    return $query->result_array();
}








// line chart




}




































































