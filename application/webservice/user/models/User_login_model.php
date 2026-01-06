<?php

class User_login_model extends CI_Model{

  private $table = 'users';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function register($data) {
        $data['added_date'] = date('Y-m-d H:i:s');
        if (isset($data['password'])) {
            $data['user_password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            unset($data['password']);
        }
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function get_by_email($email) {
        return $this->db->get_where($this->table, ['user_email'=>$email, 'status'=>"Active"])->row();
    }

    public function get_restaurant_by_id($company_id) {
        return $this->db->get_where("restaurants", ['restaurant_id'=>$company_id])->row();
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['user_id'=>$id])->row();
    }

    public function update_user($id, $data) {
        if (isset($data['password'])) {
            $data['user_password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            unset($data['password']);
        }
        $this->db->where('user_id', $id)->update($this->table, $data);
        return $this->db->affected_rows() > 0;
    }

    public function set_token($id, $token,$device_id="",$device_type="") {
      $data = ['api_token'=>$token, 'token_issued_at'=>date('Y-m-d H:i:s')];
      if($device_id != "" && $device_type != ""){
        $data['device_id'] = $device_id;
        $data['device_type'] = $device_type;
      } 
      return $this->db->where('user_id',$id)->update($this->table, $data);;
    }

    public function update_password_by_email($email, $new_password) {
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        $this->db->where('user_email', $email)
                ->where('status', 'Active'); // only update active users

        return $this->db->update($this->table, [
            'user_password' => $hashed_password,
            'updated_date'  => date('Y-m-d H:i:s')
        ]);
    }

    public function update_password_by_id($id, $new_password) {
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        $this->db->where('user_id', $id)
                ->where('status', 'Active'); // only update active users

        return $this->db->update($this->table, [
            'user_password' => $hashed_password,
            'updated_date'  => date('Y-m-d H:i:s')
        ]);
    }
    public function get_user_details($user_id=0)
    {
        $this->db->where('user_id', $user_id);

        $order = $this->db->get_where("users")->row();
        return (array) $order;
    }

}

 ?>
