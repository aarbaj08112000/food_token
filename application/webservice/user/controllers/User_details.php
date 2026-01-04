<?php defined('BASEPATH') or exit('No direct script access allowed');

class User_details extends My_Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_login_model');
        $this->load->library('form_validation');
    }

    public function index_post()
    {
        $this->getUserDetail();

        // success logic here
    }

    public function index_get(){
        $this->getUserDetail();
    }

    public function getUserDetail(){
        if ($this->authenticate() !== true) {
            return;
        }
        $user_id = $this->current_user->user_id;
        
        $success = 0;
        $message = "User data not found.";
        $data = [];
        $user_data = $this->user_login_model->get_user_details($user_id);
        if(count($user_data) > 0){
            $success = 1;
            $message = "User data fetched successfully.";
            $user_data['image'] = base_url($user_data['image']);
            $data =  $user_data;
        } 
        return  $this->response(array(
            "success" => $success,
            "message" => $message,
            'data' => $data
        ), $success == 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_NOT_FOUND);
    }

}
