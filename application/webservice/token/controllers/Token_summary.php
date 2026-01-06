<?php defined('BASEPATH') or exit('No direct script access allowed');
class Token_summary extends My_Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('token_summary_model');
    }
    public function index_post()
    {
        
        if ($this->authenticate() !== true) {
            return;
        }
        $restaurant_id = $this->current_user->restaurant_id;

        $summary_data = [];
        /* current date */
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d');
        $items_data = $this->token_summary_model->get_month_tokens($restaurant_id,$start_date,$end_date);
        $summary_data['today'] = [
            "total_revenue" => isset($items_data['total_price']) && $items_data['total_price'] > 0 ? number_format($items_data['total_price'],2) : 0,
            "total_tokens" => isset($items_data['total_tokens']) && $items_data['total_tokens'] > 0 ? number_format($items_data['total_tokens']) : 0

        ];

        /* current month */
        $start_date = date('Y-m-01');
        $end_date = date('Y-m-d');
        $items_data = $this->token_summary_model->get_month_tokens($restaurant_id,$start_date,$end_date);
        $summary_data['current_month'] = [
            "total_revenue" => isset($items_data['total_price']) && $items_data['total_price'] > 0 ? number_format($items_data['total_price'],2) : 0,
            "total_tokens" => isset($items_data['total_tokens']) && $items_data['total_tokens'] > 0 ? number_format($items_data['total_tokens']) : 0

        ];

        /* current last month */
        $start_date = date('Y-m-01', strtotime('first day of last month'));
        $end_date   = date('Y-m-t', strtotime('last month'));
        $items_data = $this->token_summary_model->get_month_tokens($restaurant_id,$start_date,$end_date);
        $summary_data['last_month'] = [
            "total_revenue" => isset($items_data['total_price']) && $items_data['total_price'] > 0 ? number_format($items_data['total_price'],2) : 0,
            "total_tokens" => isset($items_data['total_tokens']) && $items_data['total_tokens'] > 0 ? number_format($items_data['total_tokens']) : 0

        ];

        /* overall */
        
        $items_data = $this->token_summary_model->get_month_tokens($restaurant_id);
        $summary_data['overall'] = [
            "total_revenue" => isset($items_data['total_price']) && $items_data['total_price'] > 0 ? number_format($items_data['total_price'],2) : 0,
            "total_tokens" => isset($items_data['total_tokens']) && $items_data['total_tokens'] > 0 ? number_format($items_data['total_tokens']) : 0

        ];
       
        $success = 1;
        $message = "Token summary data fetch successfully";
        $data = $summary_data;

        return  $this->response(array(
            "success" => $success,
            "message" => $message,
            'data' => $data
        ), REST_Controller::HTTP_OK);
        
        
    }
    
   
}
