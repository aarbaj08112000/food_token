<?php defined('BASEPATH') or exit('No direct script access allowed');
class Token_summary_model extends CI_Model
{
    private $token = 'tokens';
    private $token_items = 'token_items';
    private $items = 'menu_items';
    public function get_month_tokens($restaurant_id = 0,$start_date = "",$end_date="")
    {
        $this->db->select('SUM(ti.qty * ti.price) AS total_price, COUNT(DISTINCT t.token_id) AS total_tokens');
        $this->db->from('tokens t');
        $this->db->join('token_items ti', 't.token_id = ti.token_id');
        $this->db->where('t.restaurant_id', $restaurant_id);
        if($start_date != "" && $end_date != ""){
            $this->db->where('t.token_date >=', $start_date);
            $this->db->where('t.token_date <=', $end_date);
        }
        $result = $this->db->get()->row_array();
        return $result;
    }

    public function get_items($items_ids = [],$restaurant_id = 0)
    {
        $this->db->where('restaurant_id', $restaurant_id);
        $this->db->where_in('item_id', $items_ids);
        $this->db->where('status', 'Active');

        $items = $this->db->get($this->items)->result();
        
        return $items;
    }
   
}
