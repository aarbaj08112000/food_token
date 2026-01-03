<?php defined('BASEPATH') or exit('No direct script access allowed');
class Bills extends My_Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Bill_model', 'bill');
    }
    public function index_post()
    {
        if ($this->authenticate() !== true)
            return;
        $this->require_role(['admin']);
        $input = $this->post();
        if (empty($input['bill']) || empty($input['items']))
            return $this->response(['status' => false, 'message' => 'bill and items required'], REST_Controller::HTTP_BAD_REQUEST);
        $input['bill']['added_by'] = $this->current_user->id;
        $id = $this->bill->create($input['bill'], $input['items']);
        return $this->response(['status' => true, 'bill_id' => $id], REST_Controller::HTTP_CREATED);
    }
}
