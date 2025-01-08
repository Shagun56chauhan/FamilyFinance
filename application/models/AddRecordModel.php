<?php

class AddRecordModel extends CI_Model
{

    public function __construct()
    {
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
    // Get all vehicle types (if you want to list available types)
    public function getVehicleTypes()
    {
        $this->db->distinct();
        $this->db->select('type');
        $query = $this->db->get('admin_vehicle');
        return $query->result_array();
    }

    public function submit_btn($user_id, $type, $reading, $date, $remarks)
    {
        $data = array(
            'user_id' => $user_id,  // Add user_id here
            'type' => $type,
            'reading' => $reading,
            'created_at' => $date,
            'remarks' => $remarks,

        );

        return $this->db->insert('vehicle', $data);
    }


    


    public function getLastReading($user_id, $type)
    {
        $this->db->select('reading');
        $this->db->from('vehicle');
        $this->db->where('user_id', $user_id);
        $this->db->where('type', $type);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit(1);

        $query = $this->db->get();
        $result = $query->row_array();

        // Return the last reading or null if no previous reading is found
        return $result ? $result['reading'] : null;
    }




}