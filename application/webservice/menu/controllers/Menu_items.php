<?php defined('BASEPATH') or exit('No direct script access allowed');
class Menu_items extends My_Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('menu_item_model', 'item');

    }
    public function get_menu($params = array())
    {
        $cid = $this->get('id') > 0 ? $this->get('id') : 0;

        $data = $this->item->list_by_category($cid);
        if ($data) {
            return $this->response(['success' => true, 'message' => 'Menus found sucessfully.', 'status' => true, 'data' => $data], REST_Controller::HTTP_OK);
        }
        return $this->response(['success' => false, 'status' => false, 'message' => 'No data Found'], REST_Controller::HTTP_BAD_REQUEST);
    }
    public function index_post()
    {
        if ($this->authenticate() !== true) {
            return;
        }

        $this->require_role(['admin']);
        $id = $this->item->insert($this->post());
        return $this->response(['status' => true, 'id' => $id], REST_Controller::HTTP_CREATED);
    }
}
