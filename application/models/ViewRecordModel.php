<?php 

class ViewRecordModel extends CI_Model
{

    public function __construct() {
        $this->load->database();
    }

    function getUsers($user_id, $type)
    {
        $this->db->where('user_id', $user_id);
      
        if ($type) {
            $this->db->where('type', $type); // Filter by selected expense type
        }
        $query = $this->db->get('vehicle');
           
      
        return $query->result_array();
    }



               
//    edit
// Get expense by ID
public function getExpenseById($id) {
    $query = $this->db->get_where('vehicle', ['id' => $id]);
    return $query->result_array();  // Return the expense as an associative array
}

// Update expense by ID
public function updateExpense($id, $data) {
    $this->db->where('id', $id);
    return $this->db->update('vehicle', $data);  // Update the expense record
}




public function deleteExpense($id) {
    return $this->db->delete('vehicle', ['id' => $id]);
}


// table 2


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

// table2
  
}




