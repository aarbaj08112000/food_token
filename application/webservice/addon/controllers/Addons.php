<?php defined('BASEPATH') or exit('No direct script access allowed');
class Addons extends My_Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Addon_group_model', 'group');
        $this->load->model('Addon_option_model', 'option');
        $this->load->model('Item_addon_group_model', 'itemaddon');
    }
    public function groups_get()
    {
        $rid = $this->get('restaurant_id');
        if (!$rid)
            return $this->response(['status' => false, 'message' => 'restaurant_id required'], REST_Controller::HTTP_BAD_REQUEST);
        $data = $this->group->list_by_restaurant($rid);
        return $this->response(['status' => true, 'data' => $data], REST_Controller::HTTP_OK);
    }
    public function options_get()
    {
        $gid = $this->get('group_id');
        if (!$gid)
            return $this->response(['status' => false, 'message' => 'group_id required'], REST_Controller::HTTP_BAD_REQUEST);
        $data = $this->option->list_by_group($gid);
        return $this->response(['status' => true, 'data' => $data], REST_Controller::HTTP_OK);
    }
    public function item_groups_post()
    {
        if ($this->authenticate() !== true)
            return;
        $this->require_role(['admin']);
        $input = $this->post();
        if (empty($input['item_id']) || !isset($input['group_ids']))
            return $this->response(['status' => false, 'message' => 'item_id & group_ids required'], REST_Controller::HTTP_BAD_REQUEST);
        $ok = $this->itemaddon->set_for_item($input['item_id'], (array) $input['group_ids']);
        return $this->response(['status' => true, 'updated' => $ok], REST_Controller::HTTP_OK);
    }
}
