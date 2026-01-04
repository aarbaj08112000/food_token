<?php defined('BASEPATH') or exit('No direct script access allowed');

class User_update extends My_Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_login_model');
        $this->load->library('form_validation');
    }

    public function index_post()
    {
        $this->updateUserRestaurant();

        // success logic here
    }
    public function updateUserRestaurant(){
        if ($this->authenticate() !== true) {
            return;
        }
        $user_id = $this->current_user->user_id;
        $config = array(
            array(
                'field' => 'user_name',
                'label' => 'Name',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'phone',
                'label' => 'Phone Number',
                'rules' => 'trim|required'
            )
        );

        $this->form_validation->set_rules($config);
        // ✅ REGISTER CALLBACK MESSAGE EXPLICITLY
        
        if (
            (!isset($_FILES['image']) || empty($_FILES['image']['name'])) && !($user_id > 0)
        ) {
            $this->response([
                'success' => 0,
                'message' => 'Validation failed',
                'errors'  => "Image required"
            ], REST_Controller::HTTP_OK);
            return;
        }

        
        if ($this->form_validation->run() === FALSE) {
            $this->response([
                'success' => 0,
                'message' => 'Validation failed',
                'errors'  => $this->form_validation->error_array()
            ], REST_Controller::HTTP_OK);
            return;
        }else{
            // collecting form data inputs
            $name = $this->security->xss_clean($this->input->post("user_name"));
            $phone = $this->security->xss_clean($this->input->post("phone"));
            $image_data = [];
            if(isset($_FILES['image']) || !empty($_FILES['image']['name'])){
                $image_data = $this->upload_image("image","public/uploads/users/");
            }
            if($image_data['upload_error']){
                $this->response(array(
                "status" => 0,
                "message" => $image_data['upload_error_msg']['error']
                ), REST_Controller::HTTP_NOT_FOUND);
            }else{
                $success = 0;
                $message = "Somthing went wrong.";
                $data = [];
                if($user_id > 0){
                    $user_data = $this->user_login_model->get_by_id($user_id);
                    $image_url = $user_data->image_url;
                    $update_arr = [
                        "user_name" => $name,
                        "phone" => $phone,
                        "image" => isset($image_data['image_url']) && $image_data['image_url'] != "" ? $image_data['image_url'] : $image_url,
                        "updated_by" => $user_id,
                        "updated_date" => date("Y-m-d H:i:s")
                    ];
                    $affected_id = $this->user_login_model->update_user($user_id,$update_arr);
                    $success = 1;
                    $message = "User details updated successfully";
                    $data['id'] = $user_id;
                }
                return  $this->response(array(
                    "success" => $success,
                    "message" => $message,
                    'data' => $data
                ), $success == 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

}
