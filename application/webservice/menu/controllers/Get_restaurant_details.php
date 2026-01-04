<?php defined('BASEPATH') or exit('No direct script access allowed');

class Get_restaurant_details extends My_Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('get_restaurant_item_model');
        $this->load->library('form_validation'); // REQUIRED
    }

    public function index_post()
    {
        $this->getRestaurantDetail();

        // success logic here
    }

    public function index_get(){
        $this->getRestaurantDetail();
    }

    public function getRestaurantDetail(){
        if ($this->authenticate() !== true) {
            return;
        }
        $user_id = $this->current_user->user_id;
        $restaurant_id = $this->current_user->restaurant_id;
        
        $success = 0;
        $message = "Restaurant data not found.";
        $data = [];
        $restaurant_item_data = $this->get_restaurant_item_model->get_restaurant_details($restaurant_id);
        if(count($restaurant_item_data) > 0){
            $success = 1;
            $message = "Restaurant item data fetched successfully.";
            $data =  $restaurant_item_data;
        }
        return  $this->response(array(
            "success" => $success,
            "message" => $message,
            'data' => $data
        ), $success == 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_NOT_FOUND);
    }

}
