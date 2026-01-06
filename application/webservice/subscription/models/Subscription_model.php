<?php defined('BASEPATH') or exit('No direct script access allowed');
class Subscription_model extends CI_Model
{
    private $users = 'users';
    public function get($user_id = 0,$restaurant_id = 0)
    {
        $details = $this->db->get_where($this->users, ['user_id' => $user_id,'restaurant_id' => $restaurant_id])->row_array();
        return $details;
    }

    public function get_items($items_ids = [],$restaurant_id = 0)
    {
        $this->db->where('restaurant_id', $restaurant_id);
        $this->db->where_in('item_id', $items_ids);
        $this->db->where('status', 'Active');

        $items = $this->db->get($this->items)->result();
        
        return $items;
    }
    public function get_tokens_details($restaurant_id = 0,$token_id = 0)
    {
        $this->db->select('t.*,r.name as restaurant_name,r.address_line1 as address,r.contact_phone as mobile');
        $this->db->from('tokens t');
        $this->db->join('restaurants r', 'r.restaurant_id = t.restaurant_id');
        $this->db->where('t.restaurant_id', $restaurant_id);
        $this->db->where('t.token_id', $token_id);
        $result = $this->db->get()->row_array();
        return $result;
    }
   
}
