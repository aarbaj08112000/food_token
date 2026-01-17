<?php defined('BASEPATH') or exit('No direct script access allowed');

class Get_restaurant_item extends My_Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('get_restaurant_item_model');
        $this->load->library('form_validation'); // REQUIRED
        $this->per_page = 12;
    }

    public function index_post()
    {
        if ($this->authenticate() !== true) {
            return;
        }
        $user_id = $this->current_user->user_id;
        $restaurant_id = $this->current_user->restaurant_id;
        $post_data = $this->input->post();
        if(!(count($post_data) > 0)){
            $data = json_decode($this->input->raw_input_stream, true);
            $_POST = $data;
        }
        
        $post_data = $this->input->post();
        $config = array(
            array(
                'field' => 'restaurant_id',
                'label' => 'Restaurant id',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_rules($config);
       
        $restaurant_id = $this->security->xss_clean($this->input->post("restaurant_id"));
        

        
        if ($this->form_validation->run() === FALSE) {
            $this->response([
                'success' => 0,
                'message' => 'Validation failed',
                'errors'  => $this->form_validation->error_array()
            ], REST_Controller::HTTP_OK);
            return;
        }else{
            // collecting form data inputs
            
            $success = 0;
            $message = "Somthing went wrong.";
            $data = [];
            $search_param = isset($post_data['search_param']) && $post_data['search_param'] != null && $post_data['search_param'] != "" ? $post_data['search_param'] : "";
            $page_count = isset($post_data['page']) && $post_data['page'] > 0 ? $post_data['page'] : 1;
            $per_page = $this->per_page > 0 ? $this->per_page : 20;
            $start_record = $page_count > 1 ? (($page_count-1) * $per_page)  : 0;
            $pagination_data = [
                "start_record" => $start_record,
                "length" => $per_page
            ];

            $restaurant_item_data = $this->get_restaurant_item_model->get($restaurant_id,$pagination_data,$search_param);
            foreach ($restaurant_item_data as $key => $value) {
                $restaurant_item_data[$key]->image_url = base_url($value->image_url);
            }
            $restaurant_item_count = $this->get_restaurant_item_model->get_count($restaurant_id,$search_param);
            $restaurant_item_count = (int) $restaurant_item_count['total_record'] > 0 ? $restaurant_item_count['total_record'] : 0;
            $next_page = ($start_record+$per_page) < $restaurant_item_count ? $post_data['page']+1 : 0;
            $success = 1;
            $message = "Restaurant item data fetched successfully.";
            $data['items_data'] = $restaurant_item_data;
            $data['next_page'] = $next_page;
            $data['total_records'] = $restaurant_item_count;
                
            return  $this->response(array(
                "success" => $success,
                "message" => $message,
                'data' => $data
            ), $success == 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_NOT_FOUND);
        }


        // success logic here
    }

}
