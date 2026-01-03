<?php defined('BASEPATH') or exit('No direct script access allowed');
class Payments extends My_Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Payment_model', 'payment');
    }
    public function index_post()
    {
        if ($this->authenticate() !== true)
            return;
        $input = $this->post();
        $input['added_by'] = $this->current_user->id;
        $id = $this->payment->create($input);
        return $this->response(['status' => true, 'payment_id' => $id], REST_Controller::HTTP_CREATED);
    }
}
