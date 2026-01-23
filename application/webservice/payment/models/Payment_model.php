<?php defined('BASEPATH') or exit('No direct script access allowed');
class Payment_model extends CI_Model
{
    private $table = 'web_hook';
    public function create($d)
    {
        $this->db->insert($this->table, $d);
        return $this->db->insert_id();
    }
    public function create_data($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
    public function get($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }
}
