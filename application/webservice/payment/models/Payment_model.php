<?php defined('BASEPATH') or exit('No direct script access allowed');
class Payment_model extends CI_Model
{
    private $table = 'web_hook';
    private $payment_table = 'payment_transaction';
    public function create($data)
    {
        $this->db->insert($this->payment_table, $data);
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
    public function get_transaction($id)
    {
        return $this->db->get_where($this->payment_table, ['transaction_id' => $id])->row();
    }
    public function get_config() {
        return $this->db->get_where("config_setting")->result();
    }
}
