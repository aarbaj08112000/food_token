<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Restaurant_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
	/* aaded for datable */
    public function get_restaurant_view_data(
        $condition_arr = [],
        $search_params = ""
    ) {
        $this->db->select(
            'r.*,ua.user_name as added_by,ua.user_name as updated_by'
        );
        $this->db->from("restaurants as r");
        $this->db->join("users as ua", "ua.user_id  = r.added_by");
        $this->db->join("users as uu", "uu.user_id  = r.updated_by","left");
        if (count($condition_arr) > 0) {
            $this->db->limit($condition_arr["length"], $condition_arr["start"]);
            if ($condition_arr["order_by"] != "") {
                $this->db->order_by($condition_arr["order_by"]);
            }
        }

        if ($search_params["date_range_filter"] != "") {
            $date_filter =  explode((" - "),$search_params["date_range_filter"]);
            $start_date = date("Y/m/d", strtotime(str_replace('/', '-', $date_filter[0])));
            $end_date = date("Y/m/d", strtotime(str_replace('/', '-', $date_filter[1])));
            // pr($date_filter,1);
               $this->db->where("STR_TO_DATE(i.grn_date, '%Y-%m-%d') BETWEEN '".$start_date."' AND '".$end_date."'");
        }
        // if (is_array($search_params) && count($search_params) > 0) {
        //     if ($search_params["value"] != "") {
        //         $search = $search_params["value"];
        //         $this->db->group_start(); // Start a group for 'like' queries
        //         $this->db->like('p.po_number', $search);
        //         $this->db->or_like('p.po_date', $search);
        //         $this->db->or_like('p.created_date', $search);
        //         $this->db->or_like('p.expiry_po_date', $search);
        //         $this->db->or_like('s.supplier_name', $search);
        //         $this->db->group_end(); // End the group
        //     }
        // }

        $result_obj = $this->db->get();
        $ret_data = is_object($result_obj) ? $result_obj->result_array() : [];

        // pr($this->db->last_query(),1);
        return $ret_data;
    }
    public function get_restaurant_view_count(
        $condition_arr = [],
        $search_params = ""
    ) {
        $this->db->select(
            'COUNT(r.restaurant_id) as total_record'
        );
        $this->db->from("restaurants as r");
        $this->db->join("users as ua", "ua.user_id  = r.added_by");
        $this->db->join("users as uu", "uu.user_id  = r.updated_by","left");
        if (count($condition_arr) > 0) {
            $this->db->limit($condition_arr["length"], $condition_arr["start"]);
            if ($condition_arr["order_by"] != "") {
                $this->db->order_by($condition_arr["order_by"]);
            }
        }

        if ($search_params["date_range_filter"] != "") {
            $date_filter =  explode((" - "),$search_params["date_range_filter"]);
            $start_date = date("Y/m/d", strtotime(str_replace('/', '-', $date_filter[0])));
            $end_date = date("Y/m/d", strtotime(str_replace('/', '-', $date_filter[1])));
            // pr($date_filter,1);
               $this->db->where("STR_TO_DATE(i.grn_date, '%Y-%m-%d') BETWEEN '".$start_date."' AND '".$end_date."'");
        }
        // if (is_array($search_params) && count($search_params) > 0) {
        //     if ($search_params["value"] != "") {
        //         $search = $search_params["value"];
        //         $this->db->group_start(); // Start a group for 'like' queries
        //         $this->db->like('p.po_number', $search);
        //         $this->db->or_like('p.po_date', $search);
        //         $this->db->or_like('p.created_date', $search);
        //         $this->db->or_like('p.expiry_po_date', $search);
        //         $this->db->or_like('s.supplier_name', $search);
        //         $this->db->group_end(); // End the group
        //     }
        // }

        $result_obj = $this->db->get();
        $ret_data = is_object($result_obj) ? $result_obj->row_array() : [];

        // pr($this->db->last_query(),1);
        return $ret_data;
    }
}
