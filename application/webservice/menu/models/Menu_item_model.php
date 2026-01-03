<?php defined('BASEPATH') or exit('No direct script access allowed');
class Menu_item_model extends CI_Model
{
    private $table = 'menu_items';
    public function list_by_category($cid)
    {
        if($cid > 0){   
            return $this->db->get_where($this->table, ['category_id' => $cid, 'is_active' => 1])->result();
        }else{
            return $this->db->get_where($this->table, ['is_active' => 1])->result();
        }
            
        
    }
    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
}
