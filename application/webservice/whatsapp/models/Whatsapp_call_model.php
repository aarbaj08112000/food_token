<?php defined('BASEPATH') or exit('No direct script access allowed');
class Whatsapp_call_model extends CI_Model
{
    private $users = 'users';
    public function get($user_id = 0,$restaurant_id = 0)
    {
        $details = $this->db->get_where($this->users, ['user_id' => $user_id,'restaurant_id' => $restaurant_id])->row_array();
        return $details;
    }

    
   
}
