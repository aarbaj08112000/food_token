<?php defined('BASEPATH') or exit('No direct script access allowed');
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
        $this->payment_success(["Started"]);
        // 🔐 Verify signature
        if (hash_hmac('sha256', $payload, $secret) !== $signature) {
            $this->payment_success(["Error"]);
            show_error('Invalid signature', 403);
            return;
        }

        $data = json_decode($payload, true);
        $event = $data['event'];

        if ($event == 'payment.captured') {
            $this->payment_success($data);
        }

        if ($event == 'payment.failed') {
            $this->payment_success($data);
        }
        
        return  $this->response(array(
            "success" => 1,
            "message" => "Payment Detail Get Successfully",
            'data' => []
        ),  REST_Controller::HTTP_OK);
    }

    public function payment_success($data){
        $details = [
            "json" => json_encode($data)
        ];
        $user_data = $this->payment_model->create_data($details);
    }
}
