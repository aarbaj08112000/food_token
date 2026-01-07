<?php
class Auth extends My_Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_login_model');
      
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
        
        $this->form_validation->set_data($input);
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('device_id', 'Device id', 'required');
        $this->form_validation->set_rules('device_type', 'Device type', 'required');
        if ($this->form_validation->run() === false) {
            
            return $this->response(['success' => 0, 'errors' => $this->form_validation->error_array()], REST_Controller::HTTP_BAD_REQUEST);
        }
        
        $user = $this->user_login_model->get_by_email($input['email']);
        if (!$user || !$this->verify_password($input['password'], $user->user_password)) {
            return $this->response(['success' => 0, 'message' => 'Invalid credentials'], REST_Controller::HTTP_UNAUTHORIZED);
        }else{
            
            $checkDate = new DateTime($user->token_issued_at);
            
            if($user->api_token != "" && $user->api_token != null && $input['bypass_unique'] != true){
                return $this->response(['success' => 0, 'message' => 'User is already logged in on another device.'], REST_Controller::HTTP_OK);
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
}