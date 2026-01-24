<?php defined('BASEPATH') or exit('No direct script access allowed');
class Payment_model extends CI_Model
{
    private $table = 'web_hook';
    private $payment_table = 'payment_transaction';
    private $subscription_table = 'subscription';
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
    public function get_transaction($id = 0)
    {
        $this->db->select('t.*');
        $this->db->from($this->payment_table.' t');
        $this->db->where('t.transaction_id', $id);
        $result = $this->db->get()->result_array();
        return $result;
    }
    public function get_config() {
        return $this->db->get_where("config_setting")->result();
    }
    public function get_subscription_data($id)
    {
        $this->db->select('t.*');
        $this->db->from($this->subscription_table.' t');
        $this->db->where('t.subscription_id', $id);
        $result = $this->db->get()->row_array();
        return $result;
    }
}
