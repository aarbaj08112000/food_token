<?php defined('BASEPATH') or exit('No direct script access allowed');
class Item_add_update_model extends CI_Model
{
    private $table = 'menu_items';
    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
    public function update($id, $data)
    {
        $this->db->where('item_id', $id);
        return $this->db->update($this->table, $data);
    }
    public function get($name="",$restaurant_id = 0,$id=0)
    {
        if($id > 0){
            $this->db->where('item_id !=', $id);
        }
        $this->db->where('restaurant_id', $restaurant_id);
        $this->db->where('name', $name);

        $order = $this->db->get_where($this->table)->row();
        return (array) $order;
    }
    public function get_details($id=0,$restaurant_id = 0)
    {
        $this->db->where('item_id', $id);
        $this->db->where('restaurant_id', $restaurant_id);

        $order = $this->db->get_where($this->table)->row();
        return (array) $order;
    }
}
