<?php
class Welcome_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    public function get_child_part_supplier($condition_arr = [],
        $search_params = "")
	 {

	    $this->db->select('gs.id as gs_id,gs.code as gs_code,u.uom_name as uom_name,s.supplier_name as supplier_name,s.supplier_name as with_in_state,cpm.*');
	    $this->db->from('child_part_master as cpm');
	    $this->db->join('child_part child','cpm.part_number = child.part_number','left');
	    $this->db->join('supplier s','s.id = cpm.supplier_id','left');
	    $this->db->join('uom u','u.id = cpm.uom_id','left');
	    $this->db->join('gst_structure gs','gs.id = cpm.gst_id','left');
	    $this->db->join('child_part_master cpm2','cpm.supplier_id = cpm2.supplier_id
				AND cpm.child_part_id = cpm2.child_part_id
				AND cpm.id < cpm2.id','left');
	    $this->db->where('cpm2.id',NULL);
	    if (count($condition_arr) > 0) {
            $this->db->limit($condition_arr["length"], $condition_arr["start"]);
            if ($condition_arr["order_by"] != "") {
                $this->db->order_by($condition_arr["order_by"]);
            }
        }
        if (is_array($search_params) && count($search_params) > 0) {
            if ($search_params["part_number"] != "") {
                $this->db->where("cpm.child_part_id", $search_params["part_number"]);
            }
            if ($search_params["value"] != "") {
                $search = $search_params["value"];
                $this->db->group_start(); // Start a group for 'like' queries
                $this->db->like('gs.code', $search);
                $this->db->or_like('u.uom_name', $search);
                $this->db->or_like('s.supplier_name', $search);
                $this->db->or_like('cpm.admin_approve', $search);
                $this->db->or_like('cpm.revision_no', $search);
                $this->db->or_like('cpm.revision_remark', $search);
                $this->db->or_like('cpm.revision_date', $search);
                $this->db->or_like('cpm.part_number', $search);
                $this->db->or_like('cpm.part_description', $search);
                $this->db->or_like('cpm.part_rate', $search);
                $this->db->group_end(); // End the group
            }
        }
	    $result_obj = $this->db->get();
	    $ret_data = is_object($result_obj) ? $result_obj->result_array() : [];

	    return $ret_data;
	}
	public function get_child_part_supplier_count(
        $condition_arr = [],
        $search_params = ""
    ) {

        $this->db->select('COUNT(gs.id) as total_record');
	    $this->db->from('child_part_master as cpm');
	    $this->db->join('child_part child','cpm.part_number = child.part_number','left');
	    $this->db->join('supplier s','s.id = cpm.supplier_id','left');
	    $this->db->join('uom u','u.id = cpm.uom_id','left');
	    $this->db->join('gst_structure gs','gs.id = cpm.gst_id','left');
	    $this->db->join('child_part_master cpm2','cpm.supplier_id = cpm2.supplier_id
				AND cpm.child_part_id = cpm2.child_part_id
				AND cpm.id < cpm2.id','left');
	    $this->db->where('cpm2.id',NULL);
        if ($search_params["part_number"] != "") {
                $this->db->where("cpm.child_part_id", $search_params["part_number"]);
            }
            if ($search_params["value"] != "") {
                $search = $search_params["value"];
                $this->db->group_start(); // Start a group for 'like' queries
                $this->db->like('gs.code', $search);
                $this->db->or_like('u.uom_name', $search);
                $this->db->or_like('s.supplier_name', $search);
                $this->db->or_like('cpm.admin_approve', $search);
                $this->db->or_like('cpm.revision_no', $search);
                $this->db->or_like('cpm.revision_remark', $search);
                $this->db->or_like('cpm.revision_date', $search);
                $this->db->or_like('cpm.part_number', $search);
                $this->db->or_like('cpm.part_description', $search);
                $this->db->or_like('cpm.part_rate', $search);
                $this->db->group_end(); // End the group
            }
	
        $result_obj = $this->db->get();
        $ret_data = is_object($result_obj) ? $result_obj->row_array() : [];
        return $ret_data;
    }
    public function get_supplier_data($condition_arr = [],
        $search_params = "")
     {

        $this->db->select('s.*');
        $this->db->from('supplier as s');
        if (count($condition_arr) > 0) {
            $this->db->limit($condition_arr["length"], $condition_arr["start"]);
            if ($condition_arr["order_by"] != "") {
                $this->db->order_by($condition_arr["order_by"]);
            }
        }
        if (is_array($search_params) && count($search_params) > 0) {
            if ($search_params["supplier_name"] != "") {
                $this->db->where("s.id", $search_params["supplier_name"]);
            }
            if ($search_params["admin_approve"] != "") {
                $this->db->where("s.admin_approve", $search_params["admin_approve"]);
            }
            // if ($search_params["part_description"] != "") {
            //     $this->db->like(
            //         "cp.part_description",
            //         $search_params["part_description"]
            //     );
            // }

            if (!empty($search_params["value"])) {
                $keyword = $search_params["value"];
                $this->db->group_start(); // Start a group of OR conditions
                
                $fields = [
                    's.supplier_name',
                    's.supplier_number',
                    's.email',
                    's.mobile_no',
                    's.gst_number',
                    's.pan_card',
                    'payment_terms'
                    // Add other relevant fields as needed
                ];
                
                foreach ($fields as $field) {
                    $this->db->or_like($field, $keyword);
                }
                
                $this->db->group_end(); // End the group of OR conditions
            }
        }
        $result_obj = $this->db->get();
        $ret_data = is_object($result_obj) ? $result_obj->result_array() : [];
        // pr($this->db->last_query(),1);
        return $ret_data;
    }
    public function get_supplier_data_count(
        $condition_arr = [],
        $search_params = ""
    ) {

        $this->db->select('COUNT(s.id) as total_record');
        $this->db->from('supplier as s');

        if (!empty($search_params["value"])) {
            $keyword = $search_params["value"];
            $this->db->group_start(); // Start a group of OR conditions
            
            $fields = [
                's.supplier_name',
                's.supplier_number',
                's.email',
                's.mobile_no',
                's.gst_number',
                's.pan_card',
                // Add other relevant fields as needed
            ];
            
            foreach ($fields as $field) {
                $this->db->or_like($field, $keyword);
            }
            
            $this->db->group_end(); // End the group of OR conditions
        }
        $result_obj = $this->db->get();
        $ret_data = is_object($result_obj) ? $result_obj->row_array() : [];

        

       
        return $ret_data;
    }
    public function get_supplier_details($supplier_id = '') {

        $this->db->select('s.*');
        $this->db->from('supplier as s');
        $this->db->where("s.id",$supplier_id);
        $result_obj = $this->db->get();
        $ret_data = is_object($result_obj) ? $result_obj->row_array() : [];
        return $ret_data;
    }
    public function update_supplier_data($update_data= array(),$supplier_id = ''){
        $this->db->where('id', $supplier_id);
        $this->db->update('supplier', $update_data);
        $this->db->affected_rows();
        $affected_rows = $this->db->affected_rows() == 0 ? 1 : $this->db->affected_rows();
        return $affected_rows;
    }
    public function get_child_part_details($child_part_id = '') {

        $this->db->select('c.*');
        $this->db->from('child_part_master as c');
        $this->db->where("c.id",$child_part_id);
        $result_obj = $this->db->get();
        $ret_data = is_object($result_obj) ? $result_obj->row_array() : [];
        return $ret_data;
    }
    public function update_child_part_data($update_data= array(),$supplier_child_part_id = ''){
        $this->db->where('id', $supplier_child_part_id);
        $this->db->update('child_part_master', $update_data);
        $this->db->affected_rows();
        $affected_rows = $this->db->affected_rows() == 0 ? 1 : $this->db->affected_rows();
        return $affected_rows;
    }

    public function getPlanningReportView($condition_arr = [],$search_params = ""){
        $month_number = $search_params['month'] != '' ? $this->get_month_number($search_params['month']) : date('m'); ;
        $year_number = $search_params['year'] != '' ? substr($search_params['year'], 3) : date('Y') ;
       
        $this->db->select('
        p.id AS planing_id,
        p.financial_year,
        p.month,
        p.clientId,
        cp.id AS customer_part_id,cp.*,
        cp.customer_id,
        c.id AS customer_id,
        c.*,
        cp_rate.rate,
        pd.schedule_qty,
        pd.schedule_qty_2,
        SUM(jc.req_qty) AS job_card_qty,
        COUNT(ns.id) AS dispatch_sales_qty,
        CASE
            WHEN pd.schedule_qty IS NOT NULL AND cp_rate.rate IS NOT NULL THEN pd.schedule_qty * cp_rate.rate
            ELSE 0
        END AS subtotal1,
        CASE
            WHEN pd.schedule_qty_2 IS NOT NULL AND cp_rate.rate IS NOT NULL THEN pd.schedule_qty_2 * cp_rate.rate
            ELSE 0
        END AS subtotal2
    ', false);
    
    $this->db->from('planing p');
    $this->db->join('customer_part cp', 'p.customer_part_id = cp.id', 'left');
    $this->db->join('customer c', 'cp.customer_id = c.id', 'left');
    $this->db->join('customer_part_rate cp_rate', 'cp.id = cp_rate.customer_master_id', 'left');
    $this->db->join('planing_data pd', 'p.id = pd.planing_id', 'left');
    $this->db->join('job_card jc', 'cp.id = jc.customer_part_id AND jc.status = "released"', 'left');
    $this->db->join('new_sales ns', 'MONTH(ns.created_date) = '.$month_number.' AND YEAR(ns.created_date) = '.$year_number.'', 'left');
    $this->db->where('p.clientId', $this->Unit->getSessionClientId());
    if(is_valid_array($search_params) && $search_params['year'] != ''){
        $this->db->where('p.financial_year',$search_params['year'] );
    }
    if(is_valid_array($search_params) && $search_params['month'] != ''){
        $this->db->where('p.month', $search_params['month']);
    }

    if(is_valid_array($search_params) && $search_params['customer'] > 0){
        $this->db->where('c.id', $search_params['customer']);
    }
    
    $this->db->group_by(array('p.id', 'cp.id', 'c.id', 'cp_rate.rate', 'pd.schedule_qty', 'pd.schedule_qty_2'));
   
    
        if($condition_arr["order_by"] == ''){    
            $this->db->order_by('p.id', 'DESC');
        }
        if (count($condition_arr) > 0) {
            $this->db->limit($condition_arr["length"], $condition_arr["start"]);
            if ($condition_arr["order_by"] != "") {
                $this->db->order_by($condition_arr["order_by"]);
            }
        }
        $result_obj = $this->db->get();
        $ret_data = is_object($result_obj) ? $result_obj->result_array() : [];
        // pr($this->db->last_query(),1);   
        return $ret_data;
    }

    public function getPlanningReportViewCount( $condition_arr = [],$search_params = ""){
        $month_number = $search_params['month'] != '' ? $this->get_month_number($search_params['month']) : date('m'); ;
        $year_number = $search_params['year'] != '' ? substr($search_params['year'], 3) : date('Y') ;
        $this->db->select('count(p.id)');
        $this->db->from('planing p');
        $this->db->join('customer_part cp', 'p.customer_part_id = cp.id', 'left');
        $this->db->join('customer c', 'cp.customer_id = c.id', 'left');
        $this->db->join('customer_part_rate cp_rate', 'cp.id = cp_rate.customer_master_id', 'left');
        $this->db->join('planing_data pd', 'p.id = pd.planing_id', 'left');
        $this->db->join('job_card jc', 'cp.id = jc.customer_part_id AND jc.status = "released"', 'left');
        $this->db->join('new_sales ns', 'MONTH(ns.created_date) = '.$month_number.' AND YEAR(ns.created_date) = '.$year_number.'', 'left');
        $this->db->where('p.clientId', $this->Unit->getSessionClientId());
        
        if(is_valid_array($search_params) && $search_params['year'] != ''){
            $this->db->where('p.financial_year',$search_params['year'] );
        }
        if(is_valid_array($search_params) && $search_params['month'] != ''){
            $this->db->where('p.month', $search_params['month']);
        }
    
        if(is_valid_array($search_params) && $search_params['customer'] > 0){
            $this->db->where('c.id', $search_params['customer']);
        }
        
        $this->db->group_by(array('p.id', 'cp.id', 'c.id', 'cp_rate.rate', 'pd.schedule_qty', 'pd.schedule_qty_2'));
        if($condition_arr["order_by"] == ''){    
            $this->db->order_by('p.id', 'DESC');
        }
       
        
        // $this->db->order_by('s.id', 'DESC');

        $result_obj = $this->db->get();
        $ret_data = is_object($result_obj) ? $result_obj->result_array() : [];        
        return $ret_data;
    }

    public function get_month_number($get_month)
	{
       
		if ($get_month == "APR") {
			return 4;
		} else if ($get_month == "MAY") {
			return 5;
		} else if ($get_month == "JUN") {
			return 6;
		} else if ($get_month == "JUL") {
			return 7;
		} else if ($get_month == "AUG") {
			return 8;
		} else if ($get_month == "SEP") {
			return 9;
		} else if ($get_month == "OCT") {
			return 10;
		} else if ($get_month == "NOV") {
			return 11;
		} else if ($get_month == "DEC") {
			return 12;
		} else if ($get_month == "JAN") {
			return 1;
		} else if ($get_month == "FEB") {
			return 2;
		} else if ($get_month == "MAR") {
			return 3;
		}
	}

    public function getSubConReportView($condition_arr = [],$search_params = ""){
       
        $clientId = $this->Unit->getSessionClientId();
        $this->db->select('
        cp.id AS challan_part_id,cp.part_id,cp.challan_id, cp.qty,cp.remaning_qty,cp.process,chn.challan_number,
        cp.remaning_qty,chn.created_date,
        chp.part_number, 
        chp.part_description,
        chn.supplier_id, 
        sup.supplier_name,
        chp.store_stock_rate as part_rate
        ');
        $this->db->from('challan_parts cp');
        $this->db->join('child_part chp', 'cp.part_id = chp.id', 'inner');
        $this->db->join('challan chn', 'cp.challan_id = chn.id', 'inner');
        $this->db->join('supplier sup', 'chn.supplier_id = sup.id', 'inner');
        $this->db->where('chn.clientId',$clientId);
        if (count($condition_arr) > 0) {
            $this->db->limit($condition_arr["length"], $condition_arr["start"]);
            if ($condition_arr["order_by"] != "") {
                $this->db->order_by($condition_arr["order_by"]);
            }
        }
        if(is_valid_array($search_params) && $search_params['part_number'] != ''){
            $this->db->where('chp.part_number',$search_params['part_number'] );
        }
        if(is_valid_array($search_params) && $search_params['suppler'] != ''){
            $this->db->where('chn.supplier_id', $search_params['suppler']);
        }
        if(is_valid_array($search_params) && $search_params['date_range'] != ''){
            $date_filter =  explode((" - "),$search_params["date_range"]);
            $data['start_date'] = $date_filter[0];
            $data['end_date'] = $date_filter[1];
           $this->db->where("STR_TO_DATE(chn.created_date, '%d-%m-%Y') BETWEEN '".$date_filter[0]."' AND '".$date_filter[1]."'");
        }

        if (!empty($search_params['value'])) {
            $keyword = $search_params['value'];
            $this->db->group_start(); // Start a group of OR conditions
            
            $fields = [
                'chp.part_number',
                'chp.part_description',
                'sup.supplier_name',
                'chn.challan_number',
                'chn.created_date',
                'cp.process'
                // Add more fields if needed
            ];
    
            foreach ($fields as $field) {
                $this->db->or_like($field, $keyword);
            }
    
            $this->db->group_end(); // End the group of OR conditions
        }

        // $this->db->group_by('cpm.part_rate');
        // if(is_valid_array($search_params) && $search_params['customer'] > 0){
        //     $this->db->where('c.id', $search_params['customer']);
        // }
        
        //  $this->db->group_by(array('p.id', 'cp.id', 'c.id', 'cp_rate.rate', 'pd.schedule_qty', 'pd.schedule_qty_2'));
   
    
        // if($condition_arr["order_by"] == ''){    
        //     $this->db->order_by('p.id', 'DESC');
        // }
        // if (count($condition_arr) > 0) {
        //     $this->db->limit($condition_arr["length"], $condition_arr["start"]);
        //     if ($condition_arr["order_by"] != "") {
        //         $this->db->order_by($condition_arr["order_by"]);
        //     }
        // }
        $result_obj = $this->db->get();
        $ret_data = is_object($result_obj) ? $result_obj->result_array() : [];
        // pr($this->db->last_query(),1);   
        return $ret_data;
    }

    public function getSubConReportViewCount( $condition_arr = [],$search_params = ""){
        $clientId = $this->Unit->getSessionClientId();
        
        $this->db->select('
        Count(cp.id)  as tot_count
        ');
        $this->db->from('challan_parts cp');
        $this->db->join('child_part chp', 'cp.part_id = chp.id', 'inner');
        $this->db->join('challan chn', 'cp.challan_id = chn.id', 'inner');
        $this->db->join('supplier sup', 'chn.supplier_id = sup.id', 'inner');
        $this->db->where('chn.clientId',$clientId);
        //$this->db->where('cpm.condition', $condition); // Replace $condition with the actual condition
        // $this->db->order_by('cp.id', 'desc');
        
        
        // if(is_valid_array($search_params) && $search_params['year'] != ''){
        //     $this->db->where('p.financial_year',$search_params['year'] );
        // }
        if(is_valid_array($search_params) && $search_params['part_number'] != ''){
            $this->db->where('chp.part_number',$search_params['part_number'] );
        }
        if(is_valid_array($search_params) && $search_params['suppler'] != ''){
            $this->db->where('chn.supplier_id', $search_params['suppler']);
        }
        if(is_valid_array($search_params) && $search_params['date_range'] != ''){
            $date_filter =  explode((" - "),$search_params["date_range"]);
            $data['start_date'] = $date_filter[0];
            $data['end_date'] = $date_filter[1];
            $this->db->where("STR_TO_DATE(chn.created_date, '%d-%m-%Y') BETWEEN '".$date_filter[0]."' AND '".$date_filter[1]."'");
        }

        if (!empty($search_params['value'])) {
            $keyword = $search_params['value'];
            $this->db->group_start(); // Start a group of OR conditions
            
            $fields = [
                'chp.part_number',
                'chp.part_description',
                'sup.supplier_name',
                'chn.challan_number',
                'chn.created_date',
                'cp.process'
                // Add more fields if needed
            ];
    
            foreach ($fields as $field) {
                $this->db->or_like($field, $keyword);
            }
    
            $this->db->group_end(); // End the group of OR conditions
        }
        
        // $this->db->group_by(array('p.id', 'cp.id', 'c.id', 'cp_rate.rate', 'pd.schedule_qty', 'pd.schedule_qty_2'));
        // if($condition_arr["order_by"] == ''){    
        //     $this->db->order_by('p.id', 'DESC');
        // }
       
        // $this->db->group_by('cpm.part_rate');
        // $this->db->order_by('s.id', 'DESC');
        $result_obj = $this->db->get();
        // pr($this->db->last_query(),1);   
        $ret_data = is_object($result_obj) ? $result_obj->row_array() : [];        
        return $ret_data;
    }

    public function getCustomerPartNumber(){
        $this->db->select('id,part_number as part');
        $this->db->from('customer_parts_master');
        $object = $this->db->get();
        $result_data = is_object($object) ? $object->result_array() : [] ;
        return $result_data;
    }

    public function getDataForCustomerParts($condition_arr = [],$search_params = []){
        $this->db->select('c.id ,c.part_number as part_number,c.part_description as part_description,c.old_fg_stock as fg_stock,c.fg_rate as fg_rate ,stock.fg_rate as stock_rate');
        $this->db->from('customer_parts_master as c');
        $this->db->join('customer_parts_master_stock stock', 'c.id = stock.customer_parts_master_id AND stock.clientId = ' . $this->db->escape($this->Unit->getSessionClientId()), 'left');
        if(is_valid_array($search_params) && $search_params['part'] > 0){
            $this->db->where('c.id', $search_params['part']);
        }
         if($condition_arr["order_by"] == ''){    
                $this->db->order_by('c.id', 'DESC');
            }
        
            if (!empty($search_params['value'])) {
                $keyword = $search_params['value'];
                $this->db->group_start(); // Start a group of OR conditions
                
                $fields = [
                    'c.part_number',
                    'c.part_description',
                    // Add more fields if needed
                ];
        
                foreach ($fields as $field) {
                    $this->db->or_like($field, $keyword);
                }
        
                $this->db->group_end(); // End the group of OR conditions
            }

        if (count($condition_arr) > 0) {
            $this->db->limit($condition_arr["length"], $condition_arr["start"]);
            if ($condition_arr["order_by"] != "") {
                $this->db->order_by($condition_arr["order_by"]);
            }
        }
        $object = $this->db->get();
        $result_data = is_object($object) ? $object->result_array() : [] ;
        return $result_data;
    }

    public function getDataForCustomerPartsCount($condition_arr = [],$search_params = []){
        $this->db->select('count(id) as total_count');
        $this->db->from('customer_parts_master');
        if(is_valid_array($search_params) && $search_params['part'] > 0){
            $this->db->where('id', $search_params['part']);
        }
        if (!empty($search_params['value'])) {
            $keyword = $search_params['value'];
            $this->db->group_start(); // Start a group of OR conditions
            
            $fields = [
                'customer_parts_master.part_number',
                'customer_parts_master.part_description',
                // Add more fields if needed
            ];
    
            foreach ($fields as $field) {
                $this->db->or_like($field, $keyword);
            }
    
            $this->db->group_end(); // End the group of OR conditions
        }
        
        $object = $this->db->get();
        $result_data = is_object($object) ? $object->row_array() : [] ;
        return $result_data;
    }

    

    /* aaded for datable */
    public function get_inwarding_view_data(
        $condition_arr = [],
        $search_params = ""
    ) {
        $current_date = date('Y-m-d');
        $clientId = $this->Unit->getSessionClientId();
        $this->db->select(
            'p.*,s.supplier_name'
        );
        $this->db->from("new_po as p");
        $this->db->join("supplier as s", "s.id = p.supplier_id",'left');
        $this->db->where("p.clientId",$clientId);
        $this->db->where("p.status","accept");
        $this->db->where("p.expiry_po_date >=",$current_date);
        if (count($condition_arr) > 0) {
            $this->db->limit($condition_arr["length"], $condition_arr["start"]);
            if ($condition_arr["order_by"] != "") {
                $this->db->order_by($condition_arr["order_by"]);
            }
        }

        if ($search_params["date_range_filter"] != "") {
                $date_filter =  explode((" - "),$search_params["date_range_filter"]);
                $data['start_date'] = $date_filter[0];
                $data['end_date'] = $date_filter[1];
               $this->db->where("STR_TO_DATE(p.created_date, '%d-%m-%Y') BETWEEN '".$date_filter[0]."' AND '".$date_filter[1]."'");
        }
        if (is_array($search_params) && count($search_params) > 0) {
            if ($search_params["value"] != "") {
                $search = $search_params["value"];
                $this->db->group_start(); // Start a group for 'like' queries
                $this->db->like('p.po_number', $search);
                $this->db->or_like('p.po_date', $search);
                $this->db->or_like('p.created_date', $search);
                $this->db->or_like('p.expiry_po_date', $search);
                $this->db->or_like('s.supplier_name', $search);
                $this->db->group_end(); // End the group
            }
        }

        $result_obj = $this->db->get();
        $ret_data = is_object($result_obj) ? $result_obj->result_array() : [];

        // pr($this->db->last_query(),1);
        return $ret_data;
    }
    public function get_inwarding_view_count(
        $condition_arr = [],
        $search_params = ""
    ) {
        $current_date = date('Y-m-d');
        $clientId = $this->Unit->getSessionClientId();
        $this->db->select(
            'count(p.po_number) as total_record'
        );
        $this->db->from("new_po as p");
        $this->db->join("supplier as s", "s.id = p.supplier_id",'left');
        $this->db->where("p.clientId",$clientId);
        $this->db->where("p.status","accept");
        $this->db->where("p.expiry_po_date >=",$current_date);
        if (count($condition_arr) > 0) {
            $this->db->limit($condition_arr["length"], $condition_arr["start"]);
            if ($condition_arr["order_by"] != "") {
                $this->db->order_by($condition_arr["order_by"]);
            }
        }
        if ($search_params["date_range_filter"] != "") {
                $date_filter =  explode((" - "),$search_params["date_range_filter"]);
                $data['start_date'] = $date_filter[0];
                $data['end_date'] = $date_filter[1];
               $this->db->where("STR_TO_DATE(p.created_date, '%d-%m-%Y') BETWEEN '".$date_filter[0]."' AND '".$date_filter[1]."'");
        }

        if (is_array($search_params) && count($search_params) > 0) {
            if (is_array($search_params) && count($search_params) > 0) {
            if ($search_params["value"] != "") {
                    $search = $search_params["value"];
                    $this->db->group_start(); // Start a group for 'like' queries
                    $this->db->like('p.po_number', $search);
                    $this->db->or_like('p.po_date', $search);
                    $this->db->or_like('p.created_date', $search);
                    $this->db->or_like('p.expiry_po_date', $search);
                    $this->db->or_like('s.supplier_name', $search);
                    $this->db->group_end(); // End the group
                }
            }
        }

        $result_obj = $this->db->get();
        $ret_data = is_object($result_obj) ? $result_obj->row_array() : [];

        // pr($this->db->last_query(),1);
        return $ret_data;
    }

     /* for datable */
     public function get_grn_validation_view_data(
        $condition_arr = [],
        $search_params = ""
    ) {
        
        $clientId = $this->Unit->getSessionClientUnitName();
        $this->db->select(
            'i.delivery_unit as delivery_unit,i.id as id,i.po_id as po_id,i.invoice_number as invoice_number,i.invoice_date as invoice_date,i.grn_number as grn_number,i.grn_date as grn_date,np.po_number as po_number,s.supplier_name as supplier_name'
        );
        $this->db->from("inwarding as i");
        $this->db->join("new_po as np", " i.po_id = np.id",'left');
        $this->db->join("supplier as s", "s.id = np.supplier_id",'left');
        $this->db->where("i.delivery_unit",$this->Unit->getSessionClientUnitName());
        $this->db->where("i.STATUS","generate_grn");
        if (count($condition_arr) > 0) {
            $this->db->limit($condition_arr["length"], $condition_arr["start"]);
            if ($condition_arr["order_by"] != "") {
                $this->db->order_by($condition_arr["order_by"]);
            }
        }

        if (is_array($search_params) && count($search_params) > 0) {
            if ($search_params["part_number"] != "") {
                $this->db->where("cp.id", $search_params["part_number"]);
            }
            if ($search_params["part_description"] != "") {
                $this->db->like(
                    "cp.part_description",
                    $search_params["part_description"]
                );
            }
            if ($search_params["value"] != "") {
                $search = $search_params["value"];
                $this->db->group_start(); // Start a group for 'like' queries
                $this->db->like('i.delivery_unit', $search);
                $this->db->or_like('i.invoice_number', $search);
                $this->db->or_like('i.invoice_date', $search);
                $this->db->or_like('i.grn_number', $search);
                $this->db->or_like('i.grn_date', $search);
                $this->db->or_like('np.po_number', $search);
                $this->db->group_end(); // End the group
            }
            
        }

        $result_obj = $this->db->get();
        $ret_data = is_object($result_obj) ? $result_obj->result_array() : [];

        // pr($this->db->last_query(),1);
        return $ret_data;
    }
    public function get_grn_validation_data_count(
        $condition_arr = [],
        $search_params = ""
    ) {
        $clientId = $this->Unit->getSessionClientId();
        $this->db->select(
            'count(i.id) as total_record'
        );
        $this->db->from("inwarding as i");
        $this->db->join("new_po as np", " i.po_id = np.id",'left');
        $this->db->join("supplier as s", "s.id = np.supplier_id",'left');
        $this->db->where("i.delivery_unit",$this->Unit->getSessionClientUnitName());
        $this->db->where("i.STATUS","generate_grn");
        if (is_array($search_params) && count($search_params) > 0) {
            if ($search_params["part_number"] != "") {
                $this->db->where("cp.id", $search_params["part_number"]);
            }
            if ($search_params["part_description"] != "") {
                $this->db->like(
                    "cp.part_description",
                    $search_params["part_description"]
                );
            }
            if ($search_params["value"] != "") {
                $search = $search_params["value"];
                $this->db->group_start(); // Start a group for 'like' queries
                $this->db->like('i.delivery_unit', $search);
                $this->db->or_like('i.invoice_number', $search);
                $this->db->or_like('i.invoice_date', $search);
                $this->db->or_like('i.grn_number', $search);
                $this->db->or_like('i.grn_date', $search);
                $this->db->or_like('np.po_number', $search);
                $this->db->group_end(); // End the group
            }
            
        }
        $result_obj = $this->db->get();
        $ret_data = is_object($result_obj) ? $result_obj->row_array() : [];

        // pr($this->db->last_query(),1);
        return $ret_data;
    }

    /* user group management  */
    public function getGroupRightsData($group_id = [],$menu_url = ''){
        $this->db->select('g.*,m.diaplay_name,m.url');
        $this->db->from('group_rights as g');
        $this->db->join("menu_master as m","m.menu_master_id = g.menu_master_id");
        $this->db->where_in("g.group_master_id",$group_id);
        if($menu_url != ""){
            $this->db->where("m.url",$menu_url);
        }
        $result_obj = $this->db->get();
        $ret_data = is_object($result_obj) ? $result_obj->result_array() : [];
        return $ret_data;
    }


    public function update_category($update_data= array(),$category_id = ''){
        $this->db->where('category_id', $category_id);
        $this->db->update('category', $update_data);
        $this->db->affected_rows();
        $affected_rows = $this->db->affected_rows() == 0 ? 1 : $this->db->affected_rows();
        return $affected_rows;
    }



    
}

?>
