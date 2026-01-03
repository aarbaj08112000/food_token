<?php defined('BASEPATH') or exit('No direct script access allowed');
class Payment_model extends CI_Model
{
    private $table = 'payments';
    public function create($d)
    {
        $this->db->insert($this->table, $d);
        return $this->db->insert_id();
    }
    public function get($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }
}
