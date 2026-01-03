<?php defined('BASEPATH') or exit('No direct script access allowed');
class Item_addon_group_model extends CI_Model
{
    private $table = 'item_addon_groups';
    public function set_for_item($item_id, $group_ids)
    {
        $this->db->trans_start();
        $this->db->where('item_id', $item_id)->delete($this->table);
        foreach ($group_ids as $g)
            $this->db->insert($this->table, ['item_id' => $item_id, 'addon_group_id' => $g]);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    public function get_by_item($item_id)
    {
        return $this->db->select('a.*')->join('addon_groups a', 'a.id=item_addon_groups.addon_group_id')->get_where($this->table, ['item_id' => $item_id])->result();
    }
}
