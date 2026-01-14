<?php defined('BASEPATH') or exit('No direct script access allowed');
class Subscription extends My_Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('subscription_model');
    }
    public function index_get()
    {
        
        $success = 0;
        $message = "Something went wrong";
        $data = [];
        
        $subscription_data = $this->subscription_model->get_subscription_details();
        return  $this->response(array(
            "success" => 1,
            "message" => "Subscription list fetch successfully.",
            'data' => $subscription_data
        ),  REST_Controller::HTTP_OK);
        
        
    }
    
   
}
