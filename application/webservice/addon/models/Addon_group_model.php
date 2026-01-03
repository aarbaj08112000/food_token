<?php defined('BASEPATH') or exit('No direct script access allowed');
class Addon_group_model extends CI_Model
{
    private $table = 'addon_groups';
    public function list_by_restaurant($rid)
    {
        return $this->db->get_where($this->table, ['restaurant_id' => $rid, 'is_active' => 1])->result();
    }
    public function insert($d)
    {
        $this->db->insert($this->table, $d);
        return $this->db->insert_id();
    }
}
