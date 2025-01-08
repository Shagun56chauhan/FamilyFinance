<?php 

class SignupModel extends CI_Model
{

    public function __construct() {
        $this->load->database();
    }

    function insertUser($data)
    {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT); // Hash the password before storing

        $this->db->insert("login", $data);
        if ($this->db->affected_rows() >= 0){
            return true; 
        } else {
            return false;   
        }
    }


    function getUsers()
    {
       $query = $this->db->get('login');
       return $query->result_array();
    }

  // Insert user into the database
  // Insert user with hashed password into the database
  public function submit_btn($name, $password, $username)
  {
      // Check if the username already exists
      if ($this->getUserByUsername($username)) {
          return false; // Return false if username already exists
      }

      // Hash the password
      $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

      $data = array(
          'name' => $name,
          'user_name' => $username,
          'password' => $hashedPassword,
      );
  
      return $this->db->insert('login', $data);
  }


  // Check if a username already exists
  public function getUserByUsername($username)
  {
    
      $query = $this->db->get_where('login', array('user_name' => $username));
        return $query->row_array();
  }
 

    
    
  


}