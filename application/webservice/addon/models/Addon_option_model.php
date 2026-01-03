<?php defined('BASEPATH') or exit('No direct script access allowed');
class Addon_option_model extends CI_Model
{
    private $table = 'addon_options';
    public function list_by_group($gid)
    {
        return $this->db->get_where($this->table, ['addon_group_id' => $gid, 'is_active' => 1])->result();
    }
    public function insert($d)
    {
        $this->db->insert($this->table, $d);
        return $this->db->insert_id();
    }
}
