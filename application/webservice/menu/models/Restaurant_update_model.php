<?php defined('BASEPATH') or exit('No direct script access allowed');
class Restaurant_update_model extends CI_Model
{
    private $table = 'restaurants';
    public function get($restaurant_id=0)
    {
        $this->db->where('restaurant_id', $restaurant_id);

        $order = $this->db->get_where($this->table)->result();
        return (array) $order;
    }
    public function update_restaurant($id, $data) {
        $this->db->where('restaurant_id', $id)->update($this->table, $data);
        return $this->db->affected_rows() > 0;
    }

}
