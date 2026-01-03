<?php defined('BASEPATH') or exit('No direct script access allowed');
class Bill_model extends CI_Model
{
    private $table = 'bills';
    private $bill_items = 'bill_items';
    public function create($b, $items = [])
    {
        $this->db->trans_start();
        $this->db->insert($this->table, $b);
        $bid = $this->db->insert_id();
        foreach ($items as $it) {
            $it['bill_id'] = $bid;
            $this->db->insert($this->bill_items, $it);
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
            return false;
        return $bid;
    }
}
