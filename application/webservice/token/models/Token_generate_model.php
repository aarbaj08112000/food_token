<?php defined('BASEPATH') or exit('No direct script access allowed');
class Token_generate_model extends CI_Model
{
    private $token = 'tokens';
    private $token_items = 'token_items';
    private $items = 'menu_items';
    public function generate_token($payload)
    {
        $this->db->trans_start();
        $date = date("Y-m-d");
        $token_data = $this->get($date,$payload['restaurant_id']);
        $token_number = count($token_data) > 0 ? count($token_data) + 1 : 1;
        $token_items = $payload['items'];
        unset($payload['items']);
        $payload['token_number'] = $token_number;
        $token_insert_arr = $payload;
        $this->db->insert($this->token, $token_insert_arr);
        $token_id = $this->db->insert_id();
        foreach ($token_items as $key=>$it) {
            $token_items[$key]['token_id'] = $token_id;
        }
        $this->db->insert_batch($this->token_items, $token_items);
        $this->db->trans_complete();
        $result_arr = [];
        if ($this->db->trans_status() === FALSE)
            return ["token_id"=>0];
        return ["token_id"=>$token_id,"token_number"=>$token_number];
    }
    public function get($date = "",$restaurant_id = 0)
    {
        
        $tokens = $this->db->get_where($this->token, ['restaurant_id' => $restaurant_id,'token_date' => $date])->result();
        return $tokens;
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
