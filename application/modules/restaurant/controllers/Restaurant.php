<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Restaurant extends MY_Controller {
	public function __construct() {
        parent::__construct();
        $this->load->model('Restaurant_model');
    }
	public function restaurant_list()
    {
        /* datatable */
        $column[] = [
            "data" => "restaurant_id",
            "title" => "Id",
            "width" => "7%",
            "className" => "dt-center",
            "visible" => false
        ];
        $column[] = [
            "data" => "name",
            "title" => "Name",
            "width" => "14%",
            "className" => "dt-left",
        ];
		$column[] = [
            "data" => "contact_email",
            "title" => "Email",
            "width" => "14%",
            "className" => "dt-left",
        ];
		$column[] = [
            "data" => "contact_phone",
            "title" => "Contact Number",
            "width" => "14%",
            "className" => "dt-left",
        ];
		$column[] = [
            "data" => "address_line1",
            "title" => "Address",
            "width" => "14%",
            "className" => "dt-left",
        ];
		
		$column[] = [
            "data" => "added_by",
            "title" => "Added By",
            "width" => "14%",
            "className" => "dt-left",
        ];
		$column[] = [
            "data" => "added_date",
            "title" => "Added Date",
            "width" => "14%",
            "className" => "dt-left",
        ];
		$column[] = [
            "data" => "updated_by",
            "title" => "Updated By",
            "width" => "14%",
            "className" => "dt-left",
        ];
		$column[] = [
            "data" => "updated_date",
            "title" => "Updated Date",
            "width" => "14%",
            "className" => "dt-left",
        ];
		$column[] = [
            "data" => "status",
            "title" => "Status",
            "width" => "14%",
            "className" => "dt-left",
        ];
        $data["data"] = $column;
        $data["is_searching_enable"] = true;
        $data["is_paging_enable"] = true;
        $data["is_serverSide"] = true;
        $data["is_ordering"] = true;
        $data["is_heading_color"] = "#a18f72";
        $data["no_data_message"] =
            '<div class="p-3 no-data-found-block"><img class="p-2" src="' .
            base_url() .
            'public/assets/images/images/no_data_found_new.png" height="150" width="150"><br> No Part GRN data found..!</div>';
        $data["is_top_searching_enable"] = true;
        $data["sorting_column"] = json_encode([[0, 'desc']]);
        $data["page_length_arr"] = [[10,50,100,200], [10,50,100,200]];
        $data["admin_url"] = base_url();
        $data["base_url"] = base_url();
		$this->smarty->loadView('restaurant_list.tpl', $data,'Yes','Yes');
    }
    public function get_restaurant_list()
    {
        $post_data = $this->input->post();
        $column_index = array_column($post_data["columns"], "data");
        $order_by = "";
        foreach ($post_data["order"] as $key => $val) {
            if ($key == 0) {
                $order_by .= $column_index[$val["column"]] . " " . $val["dir"];
            } else {
                $order_by .=
                    "," . $column_index[$val["column"]] . " " . $val["dir"];
            }
        }
        $condition_arr["order_by"] = $order_by;
        $condition_arr["start"] = $post_data["start"];
        $condition_arr["length"] = $post_data["length"];
        $base_url = $this->config->item("base_url");
        
        $data = $this->Restaurant_model->get_restaurant_view_data(
            $condition_arr,
            $post_data["search"]
        );
        // pr($data,1);

        foreach ($data as $key => $value) {
            $data[$key]['status'] = $value['is_active'] ? "Active" : "Inactive";
			$data[$key]['added_by'] = display_no_character($value['added_by']);
			$data[$key]['added_date'] = display_no_character($value['added_date']);
			$data[$key]['updated_by'] = display_no_character($value['updated_by']);
			$data[$key]['updated_date'] = display_no_character($value['updated_date']);
        }
        $data["data"] = $data;
        $total_record = $this->Restaurant_model->get_restaurant_view_count([], $post_data["search"]);
        $data["recordsTotal"] = $total_record['total_record'];
        $data["recordsFiltered"] = $total_record['total_record'];
        echo json_encode($data);
        exit();
        
    }
}

