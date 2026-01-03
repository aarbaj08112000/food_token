<?php

class Test_model extends CI_Model{

  public function __construct(){
    parent::__construct();
    $this->load->database();
  }

  public function check_user_login($data = array()){
    $this->db->select("u.*");
    $this->db->from("userinfo as u");
    $this->db->where("u.user_email", $data['email']);
    $this->db->where("u.user_password", $data['password']);
    $result_obj = $this->db->get();
    $ret_data = is_object($result_obj) ? $result_obj->result_array() : [];
    $insert_id = 0;
    if(count($ret_data) > 0){
        $insert_id = $ret_data[0]['id'];
    }else{
        $insert_id = -1;
    }
    return $insert_id;
}

}

 ?>
