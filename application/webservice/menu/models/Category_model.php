<?php defined('BASEPATH') or exit('No direct script access allowed');
class Category_model extends CI_Model
{
    private $table = 'categories';
    public function list_by_restaurant($rid)
    {
        return $this->db->get_where($this->table, ['restaurant_id' => $rid, 'is_active' => 1])->result();
    }
    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
}
