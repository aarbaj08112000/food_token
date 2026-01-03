<?php defined('BASEPATH') or exit('No direct script access allowed');
class Order extends My_Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('order_model');
    }
    public function index_post()
    {
        
        if ($this->authenticate() !== true)
            return;
        $input = $this->post();
        if (empty($input['order']) || empty($input['items']))
            return $this->response(['success' => false,'status' => false, 'message' => 'order and items required'], REST_Controller::HTTP_BAD_REQUEST);
        $input['order']['added_by'] = $this->current_user->user_id;
        $id = $this->order_model->create_order($input);
        if (!$id)
            return $this->response(['success' => false, 'status' => false, 'message' => 'Failed'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        return $this->response(['success' => true, 'message' => 'Order added succesfullly','status' => true, 'order_id' => $id], REST_Controller::HTTP_CREATED);
    }
    public function index($id = null)
    {   
        
        if ($this->authenticate() !== true)
            return;
        if ($id) {
            $o = $this->order_model->get($id);
           
            if (!$o)
                return $this->response(['success' => false, 'status' => false, 'message' => 'Not found'], REST_Controller::HTTP_NOT_FOUND);
            // if ($o->added_by != $this->current_user->user_id)
                // return $this->response(['success' => false,'status' => false, 'message' => 'Forbidden'], REST_Controller::HTTP_FORBIDDEN);
            return $this->response(['success' => true,'status' => true, 'data' => $o], REST_Controller::HTTP_OK);

        }
        $filters = [];
        // if ($this->current_user->role === 'customer')
        $filters['user_id'] = $this->current_user->user_id;
        if ($this->get('restaurant_id'))
            $filters['restaurant_id'] = $this->get('restaurant_id');
        $list = $this->order_model->list($filters);
        return $this->response(['success' => true,'status' => true, 'data' => $list], REST_Controller::HTTP_OK);
    }


   
}
