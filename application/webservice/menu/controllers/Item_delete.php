<?php defined('BASEPATH') or exit('No direct script access allowed');

class Item_delete extends My_Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('item_add_update_model');
        $this->load->library('form_validation'); // REQUIRED
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

        $config = array(
            array(
                'field' => 'item_id',
                'label' => 'Item id',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_rules($config);
        $item_id = $this->security->xss_clean($this->input->post("item_id"));
        
        
        if ($this->form_validation->run() === FALSE) {
            $this->response([
                'success' => 0,
                'message' => 'Validation failed',
                'errors'  => $this->form_validation->error_array()
            ], REST_Controller::HTTP_OK);
            return;
        }else{
            
            if(isset($_FILES['image']) || !empty($_FILES['image']['name'])){
                $image_data = $this->upload_image("image","public/uploads/item/");
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
                $update_arr = [
                    "status" => "Inactive",
                    "updated_by" => $user_id,
                    "updated_date" => date("Y-m-d H:i:s")
                ];
                $affected_id = $this->item_add_update_model->update($item_id,$update_arr);
                $success = 1;
                $message = "Item delete successfully";
                return  $this->response(array(
                    "success" => $success,
                    "message" => $message,
                    'data' => $data
                ), $success == 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_NOT_FOUND);
            }
        }


        // success logic here
    }

}
