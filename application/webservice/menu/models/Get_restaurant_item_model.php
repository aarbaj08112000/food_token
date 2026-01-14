<?php defined('BASEPATH') or exit('No direct script access allowed');
class Get_restaurant_item_model extends CI_Model
{
    private $table = 'menu_items';
    public function get($restaurant_id=0,$pagination_data = array())
    {
        $this->db->where('restaurant_id', $restaurant_id);
        $this->db->where('status', "Active");
        $this->db->limit($pagination_data["length"], $pagination_data["start_record"]);
        $order = $this->db->get_where($this->table)->result();
        // pr($this->db->last_query(),1);
        return (array) $order;
    }
    public function get_count($restaurant_id=0)
    {
        $this->db->select('COUNT(item_id) as total_record');
        $this->db->from($this->table);
        $this->db->where('status', "Active");
        $this->db->where('restaurant_id', $restaurant_id);
        $result = $this->db->get()->row_array();
        return $result;
    }
    public function get_restaurant_details($restaurant_id=0)
    {
        $this->db->where('restaurant_id', $restaurant_id);

        $order = $this->db->get_where("restaurants")->row();
        return (array) $order;
    }
}
