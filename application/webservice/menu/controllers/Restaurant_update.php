<?php defined('BASEPATH') or exit('No direct script access allowed');

class Restaurant_update extends My_Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('restaurant_update_model');
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
                'field' => 'restaurant_id',
                'label' => 'Restaurant id',
                'rules' => 'required'
            ),
            array(
                'field' => 'name',
                'label' => 'Name',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'phone',
                'label' => 'Contact Number',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'address',
                'label' => 'Address',
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
            $restaurant_id = $this->security->xss_clean($this->input->post("restaurant_id"));
            $name = $this->security->xss_clean($this->input->post("user_name"));
            $contact_phone = $this->security->xss_clean($this->input->post("phone"));
            $address_line1 = $this->security->xss_clean($this->input->post("address"));
            $image_data = [];
            if(isset($_FILES['image']) || !empty($_FILES['image']['name'])){
                $image_data = $this->upload_image("image","public/uploads/restaurant/");
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
                if($restaurant_id > 0){
                    $restauranr_data = $this->restaurant_update_model->get($restaurant_id);
                    $image_url = $restauranr_data[0]->logo_url;
                    
                    $update_arr = [
                        "name" => $name,
                        "contact_phone" => $contact_phone,
                        "address_line1" => $address_line1,
                        "logo_url" => isset($image_data['image_url']) && $image_data['image_url'] != "" ? $image_data['image_url'] : $image_url,
                        "updated_by" => $user_id,
                        "updated_date" => date("Y-m-d H:i:s")
                    ];
                    $affected_id = $this->restaurant_update_model->update_restaurant($restaurant_id,$update_arr);
                    $success = 1;
                    $message = "Restaurant details updated successfully";
                    $data['id'] = $restaurant_id;
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
