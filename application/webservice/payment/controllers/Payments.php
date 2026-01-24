<?php defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . "third_party/Razorpay/Razorpay.php";
use Razorpay\Api\Api;
class Payments extends My_Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('payment_model');
    }
    public function payment_web_hook()
    {
        $payload = file_get_contents('php://input');
        $signature = $_SERVER['HTTP_X_RAZORPAY_SIGNATURE'] ?? '';

        $secret = 'myWebhookSecret@2026';
        // 🔐 Verify signature
        if (hash_hmac('sha256', $payload, $secret) !== $signature) {
            $this->payment_error(["Error"]);
            show_error('Invalid signature', 403);
            return;
        }

        $data = json_decode($payload, true);
        $event = $data['event'];

        if ($event == 'payment.captured') {
            $this->payment_success($data);
        }

        if ($event == 'payment.failed') {
            $this->payment_faild($data);
        }
        
        
        return  $this->response(array(
            "success" => 1,
            "message" => "Payment Detail Get Successfully",
            'data' => []
        ),  REST_Controller::HTTP_OK);
    }

    public function payment_error($data){
        $details = [
            "json" => json_encode($data)
        ];
        $user_data = $this->payment_model->create_data($details);
    }
    public function payment_faild($data){
        try{
        $response = $data['payload']['payment']['entity'];
        $insert_data = [
            "transaction_id" => $response['id'],
            "order_id" => $response['id'],
            "user_id" => $response['notes']['user_id'],
            "amount" => $response['amount'],
            "subscription_id" =>  $response['notes']['subscription_id'],
            "error_code" => $response['error_code'],
            "error_description" => $response['error_description'],
            "status" => $response['status'],
            "response_json" => json_encode($data)
        ];
        $get_transaction = $this->payment_model->get_transaction($insert_data['transaction_id']);   
        if((count($get_transaction) == 0)){
            $payment_entry = $this->payment_model->create($insert_data);
        }
        
        }catch (Exception $e) {
            // $this->payment_error($data);
        }
    }

    public function payment_success($data){
        try{
        $response = $data['payload']['payment']['entity'];
        $insert_data = [
            "transaction_id" => $response['id'],
            "order_id" => $response['id'],
            "user_id" => $response['notes']['user_id'],
            "amount" => $response['amount'],
            "subscription_id" =>  $response['notes']['subscription_id'],
            "error_code" => $response['error_code'],
            "error_description" => $response['error_description'],
            "status" => $response['status'],
            "response_json" => json_encode($data)
        ];
        $get_transaction = $this->payment_model->get_transaction($insert_data['transaction_id']);   
        if((count($get_transaction) == 0)){
            $payment_entry = $this->payment_model->create($insert_data);
        }
        $get_subscription_data = $this->payment_model->get_subscription_data($response['notes']['subscription_id']);
        $date = $this->addMonthsToCurrentDate($get_subscription_data['month']);
        $date = $date." 23:59:00";
        $this->payment_error([$date]);

        
        }catch (Exception $e) {
            // $this->payment_success(["Error"]);
        }
    }

    function addMonthsToCurrentDate($months, $format = 'Y-m-d')
    {
    $date = new DateTime(); // current date
    $date->modify("+$months month");
    return $date->format($format);
    }


    public function generate_order_id()
    {
        if ($this->authenticate() !== true) {
            return;
        }
        $post_data = $this->input->post();
        if(!(count($post_data) > 0)){
            $data = json_decode($this->input->raw_input_stream, true);
            $post_data = $_POST = $data;
        }
        $input = $this->post();
        $this->form_validation->set_data($input);
        $this->form_validation->set_rules('subscription_id', 'Subscription Id', 'required');
        $this->form_validation->set_rules('amount', 'amount', 'required');
        if ($this->form_validation->run() === false) {
            return $this->response(['success' => 0, 'errors' => $this->form_validation->error_array(),"data" => (object)[]], REST_Controller::HTTP_BAD_REQUEST);
        }
        $user_id = $this->current_user->user_id;

        $config_data = $this->payment_model->get_config();
        $config_data_return = [];
        $provide_config = ["payment_gateway_id","payment_gateway_secret_key"];
        foreach ($config_data as $key => $value) {
            if(in_array($value->name,$provide_config)){
                $config_data_return[$value->name] = $value->value;
            }
        }
        $api = new Api($config_data_return['payment_gateway_id'], $config_data_return['payment_gateway_secret_key']);
        $subscription_id = $post_data['subscription_id'];
        $amount = $post_data['amount'];
        $orderData = [
            'amount' => $amount * 100, // in paise
            'currency' => 'INR',
            'receipt' => 'SUB_' . $subscription_id,
            'notes' => [
                'subscription_id' => $subscription_id,
                'user_id' => $user_id
            ]
        ];

        // Create order in Razorpay
        $order = $api->order->create($orderData);

        return  $this->response(array(
            "success" => 1,
            "message" => "Order generated successfully",
            'data' => [
                "order_id" => $order['id']
            ]
        ),  REST_Controller::HTTP_OK);
    }
}
