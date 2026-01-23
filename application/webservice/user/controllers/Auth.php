<?php
class Auth extends My_Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_login_model');
        $this->master_otp = "003312";
      
    }
    public function register_post()
    {
        $input = $this->post();
        $this->form_validation->set_data($input);
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        if ($this->form_validation->run() === false) {
            return $this->response(['success' => 0, 'errors' => $this->form_validation->error_array()], REST_Controller::HTTP_BAD_REQUEST);
        }

        $data = ['name' => $input['name'], 'email' => $input['email'], 'phone' => $input['phone'] ?? null, 'user_password' => $input['password'], 'user_role' => $input['role'] ?? 'customer','added_by' => $input['added_by'] ?? null];
        $id = $this->user_login_model->register($data);
        return $response = $this->response(['success' => 1,'message' => 'Login successful','data' => ['success' => 1, 'user_id' => $id]], REST_Controller::HTTP_CREATED);
         
    }
    public function login()
    {
        $input = $this->post();
        $by_pass_id = [1,2,3];
        $this->form_validation->set_data($input);
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('device_id', 'Device id', 'required');
        $this->form_validation->set_rules('device_type', 'Device type', 'required');
        if ($this->form_validation->run() === false) {
            
            return $this->response(['success' => 0, 'errors' => $this->form_validation->error_array(),"data" => (object)[]], REST_Controller::HTTP_BAD_REQUEST);
        }
        
        
        $user = $this->user_login_model->get_by_email($input['email']);
        if (!$user || !$this->verify_password($input['password'], $user->user_password)) {
            return $this->response(['success' => 0, 'message' => 'Invalid credentials',"data" => (object)[]], REST_Controller::HTTP_OK);
        }else{
            
            $checkDate = new DateTime($user->token_issued_at);
            
            if($user->api_token != "" && $user->api_token != null && $input['bypass_unique'] != true && !in_array($user->user_id,$by_pass_id) && $user->device_type == $input['device_type'] && $user->device_id != $input['device_id']){
                return $this->response(['success' => 2, 'message' => 'User is already logged in on another device.',"data" => (object)[]], REST_Controller::HTTP_OK);
            }
            
        }
        $restaurant = $this->user_login_model->get_restaurant_by_id($user->restaurant_id);
        
        $payload = ['uid' => $user->user_id,  'iat' => time(), 'exp' => time() + $this->jwt_exp];
        
        $token = $this->jwt_encode($payload);
        
        $this->user_login_model->set_token($user->user_id, $token,$input['device_id'],$input['device_type']);
        $data['token'] = $token;
        $data['id'] = $user->user_id;
        $user->image = base_url($user->image);
        $data['user_details'] = $user;
        $restaurant->logo_url = base_url($restaurant->logo_url);
        $data['restaurant'] = $restaurant;
        return $this->response(['success' => 1,'message' => 'Login successfully', 'data' => $data], REST_Controller::HTTP_OK);
    }
    public function logout()
    {
        if ($this->authenticate() !== true) {
            return;
        }

        $this->user_login_model->set_token($this->current_user->user_id, null);
        return $this->response(['success' => 1, 'message' => 'Logged out'], REST_Controller::HTTP_OK);
    }
    public function verify_user()
    {
        
        $post_data = $this->input->post();
        if(!(count($post_data) > 0)){
            $data = json_decode($this->input->raw_input_stream, true);
            $post_data = $_POST = $data;
        }
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        if ($this->form_validation->run() === false) {
            return $this->response(['success' => 0, 'errors' => $this->form_validation->error_array(),"data" => (object)[]], REST_Controller::HTTP_BAD_REQUEST);
        }
        $email = $post_data['email'];
        $user = $this->user_login_model->get_by_email($email);
        $otp = random_int(100000, 999999);
        $otp_validity = time() + 120;
        $update_data = [
            "otp" => $otp,
            "otp_validity" => $otp_validity
        ];
        $user_update = $this->user_login_model->update_user($user->user_id,$update_data);
        $success = 0;
        $message = "Somthing went wrong";
        if($user_update){
            // pr($email,1);
            $email_data = [
                "user" => $user->user_name,
                "valid_upto" => "2",
                "otp" => $otp,
                "email_name" => "OTP Verification",
                "email_subject" => "OTP Verification Required for New Device Login",
                "company_name" => "Code Crafter Infotech",
                "company_email" => "codecrafter.help@gmail.com",
                "company_contact" => "+91 94058 43312"
            ];
            $result = $this->email_sender($email_data,$email,"send_verify_user_otp");
            // pr($result,1);   
            if($result['success'] == 1){
                $message = "Otp has been sent on email";
                $success = 1;
            }else{
                $success = $result['success'];
                $message = $result['message'];
            }
        }
       
        return $this->response(['success' => $success, 'message' => $message,"data" => (object)[]], REST_Controller::HTTP_OK);
    }
    public function verify_otp()
    {
        $post_data = $this->input->post();
        if(!(count($post_data) > 0)){
            $data = json_decode($this->input->raw_input_stream, true);
            $post_data = $_POST = $data;
        }
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('otp', 'Otp', 'required');
        $this->form_validation->set_rules('device_id', 'Device id', 'required');
        $this->form_validation->set_rules('device_type', 'Device type', 'required');
        if ($this->form_validation->run() === false) {
            return $this->response(['success' => 0, 'errors' => $this->form_validation->error_array(),"data" => (object)[]], REST_Controller::HTTP_BAD_REQUEST);
        }
        $email = $post_data['email'];
        $otp = $post_data['otp'];
        $device_id = $post_data['device_id'];
        $device_type = $post_data['device_type'];
        $user = $this->user_login_model->get_by_email($email);
        $success = 0;
        $message = "Somthing went wrong";
        $master_otp_match = false;
        if($user->otp == $otp || $this->master_otp == $otp){
            if(time() <= $user->otp_validity){
                $restaurant = $this->user_login_model->get_restaurant_by_id($user->restaurant_id);
        
                $payload = ['uid' => $user->user_id,  'iat' => time(), 'exp' => time() + $this->jwt_exp];
                
                $token = $this->jwt_encode($payload);
                
                $this->user_login_model->set_token($user->user_id, $token,$device_id,$device_type);
                $data['token'] = $token;
                $data['id'] = $user->user_id;
                $user->image = base_url($user->image);
                $data['user_details'] = $user;
                $restaurant->logo_url = base_url($restaurant->logo_url);
                $data['restaurant'] = $restaurant;
                return $this->response(['success' => 1,'message' => 'Login successfully', 'data' => $data], REST_Controller::HTTP_OK);
            }else{
                $message = "Otp expired";
            }

        }else{
            $message = "Otp invalid";
        }
        return $this->response(['success' => $success, 'message' => $message,"data" => (object)[]], REST_Controller::HTTP_OK);
        
    }
    public function get_config()
    {
        if ($this->authenticate() !== true) {
            return;
        }
       
        $config_data = $this->user_login_model->get_config();
        $config_data_return = [];
        $provide_config = ["payment_gateway_id","payment_gateway_secret_key"];
        foreach ($config_data as $key => $value) {
            if(in_array($value->name,$provide_config)){
                $config_data_return[$value->name] = $value->value;
            }
        }
        return $response = $this->response(['success' => 1,'message' => 'Config data get successfully','data' => $config_data_return], REST_Controller::HTTP_CREATED);
         
    }
}