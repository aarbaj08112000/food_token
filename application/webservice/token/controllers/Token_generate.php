<?php defined('BASEPATH') or exit('No direct script access allowed');
class Token_generate extends My_Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('token_generate_model');
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
                'field' => 'items',
                'label' => 'Items',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_rules($config);
        $items = $this->security->xss_clean($this->input->post("items"));
        $success = 0;
        $message = "Something went wrong";
        $data = [];
        if(count($items) > 0){
            $items_ids = array_column($items,"id");
            $items_data = $this->token_generate_model->get_items($items_ids,$restaurant_id);
            
            $items_data = array_column($items_data,"base_price","item_id");
            $insert_items = [];
            foreach ($items as $key => $value) {
                $insert_items[] = [
                    "item_id" => $value['id'],
                    "qty" => $value['qty'],
                    "price" => $items_data[$value['id']] > 0 ? $items_data[$value['id']] : 0,
                ];
            }
            $payload = [
                "restaurant_id" => $restaurant_id,
                "token_date" => date("Y-m-d"),
                "token_time" => date("H:i:s"),
                "added_by" => $user_id,
                "added_date" => date("Y-m-d H:i:s"),
                "items" => $insert_items
            ];
            $token_data = $this->token_generate_model->generate_token($payload);
            if($token_data['token_id'] > 0 ){
                $success = 1;
                $message = "Token generated sucessfully";
                $data = [
                    "token_number" => $token_data['token_number'],
                    "url" => base_url("public/uploads/token/1/104.pdf")
                ];
            }
        }else{
            $message = "Please add item for token";
        }

        return  $this->response(array(
            "success" => $success,
            "message" => $message,
            'data' => $data
        ), $success == 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_NOT_FOUND);
        
        
    }
    
   
}
