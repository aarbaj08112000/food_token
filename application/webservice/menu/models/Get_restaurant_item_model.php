<?php defined('BASEPATH') or exit('No direct script access allowed');
class Get_restaurant_item_model extends CI_Model
{
    private $table = 'menu_items';
    public function get($restaurant_id=0)
    {
        $this->db->where('restaurant_id', $restaurant_id);

        $order = $this->db->get_where($this->table)->result();
        return (array) $order;
    }
    public function get_restaurant_details($restaurant_id=0)
    {
        $this->db->where('restaurant_id', $restaurant_id);

        $order = $this->db->get_where("restaurants")->row();
        return (array) $order;
    }
}
