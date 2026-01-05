<?php defined('BASEPATH') or exit('No direct script access allowed');

class Item_add_update extends My_Api_Controller
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
                'field' => 'name',
                'label' => 'Name',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'description',
                'label' => 'Description',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'price',
                'label' => 'Price',
                'rules' => 'required|numeric'
            )
        );

        $this->form_validation->set_rules($config);
        $id = $this->security->xss_clean($this->input->post("id"));
        // ✅ REGISTER CALLBACK MESSAGE EXPLICITLY
        
        if (
            (!isset($_FILES['image']) || empty($_FILES['image']['name'])) && !($id > 0)
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
            $name = $this->security->xss_clean($this->input->post("name"));
            $description = $this->security->xss_clean($this->input->post("description"));
            $price = $this->security->xss_clean($this->input->post("price"));
            $image_data = [];
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
                if($id > 0){

                    $item_data = $this->item_add_update_model->get_details($id,$restaurant_id);
                    $image_url = $item_data['image_url'];
                    $update_arr = [
                        "name" => $name,
                        "description" => $description,
                        "base_price" => $price,
                        "image_url" => $image_data['image_url'] != "" ? $image_data['image_url'] : $image_url,
                        "updated_by" => $user_id,
                        "updated_date" => date("Y-m-d H:i:s")
                    ];
                    $item_data = $this->item_add_update_model->get($name,$restaurant_id,$id);
                    if(count($item_data) > 0 ){
                        $message = "Item already added with this name";
                    }else{
                        $affected_id = $this->item_add_update_model->update($id,$update_arr);
                        $success = 1;
                        $message = "Item updated successfully";
                        $data['id'] = $id;
                    }
                }else{
                    $insert_arr = [
                        "name" => $name,
                        "description" => $description,
                        "base_price" => $price,
                        "image_url" => $image_data['image_url'],
                        "added_by" => $user_id,
                        "restaurant_id" => $restaurant_id,
                        "added_date" => date("Y-m-d H:i:s")
                    ];
                    $item_data = $this->item_add_update_model->get($name,$restaurant_id);
                    if(count($item_data) > 0 ){
                        $message = "Item already added with this name";
                    }else{
                        $insert_id = $this->item_add_update_model->insert($insert_arr);
                        if($insert_id > 0){
                            $success = 1;
                            $message = "Item added successfully";
                            $data['insert_id'] = $insert_id;
                        }
                    }
                   
                }
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
