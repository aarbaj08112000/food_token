<?php defined('BASEPATH') or exit('No direct script access allowed');

class Get_restaurant_item extends My_Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('get_restaurant_item_model');
        $this->load->library('form_validation'); // REQUIRED
    }

    public function index_post()
    {
        if ($this->authenticate() !== true) {
            return;
        }
        $user_id = $this->current_user->user_id;
        $restaurant_id = $this->current_user->restaurant_id;
        

        $config = array(
            array(
                'field' => 'restaurant_id',
                'label' => 'Restaurant id',
                'rules' => 'required|numeric'
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
            $restaurant_item_data = $this->get_restaurant_item_model->get($restaurant_id);
            $success = 1;
            $message = "Restaurant item data fetched successfully.";
            $data['items_data'] = $restaurant_item_data;
                
            return  $this->response(array(
                "success" => $success,
                "message" => $message,
                'data' => $data
            ), $success == 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_NOT_FOUND);
        }


        // success logic here
    }

}
