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
            return $this->response(['status' => false, 'errors' => $this->form_validation->error_array()], REST_Controller::HTTP_BAD_REQUEST);
        }

        $data = ['name' => $input['name'], 'email' => $input['email'], 'phone' => $input['phone'] ?? null, 'user_password' => $input['password'], 'user_role' => $input['role'] ?? 'customer','added_by' => $input['added_by'] ?? null];
        $id = $this->user_login_model->register($data);
        return $response = $this->response(['success' => true,'message' => 'Login successful','data' => ['status' => true, 'user_id' => $id]], REST_Controller::HTTP_CREATED);
         
    }
    public function login()
    {
        $input = $this->post();
        
        $this->form_validation->set_data($input);
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');
        if ($this->form_validation->run() === false) {
            
            return $this->response(['status' => false, 'errors' => $this->form_validation->error_array()], REST_Controller::HTTP_BAD_REQUEST);
        }
        
        $user = $this->user_login_model->get_by_email($input['email']);
        if (!$user || !$this->verify_password($input['password'], $user->user_password)) {

            return $this->response(['status' => false, 'message' => 'Invalid credentials'], REST_Controller::HTTP_UNAUTHORIZED);
        }
        $restaurant = $this->user_login_model->get_restaurant_by_id($user->restaurant_id);
        
        $payload = ['uid' => $user->user_id,  'iat' => time(), 'exp' => time() + $this->jwt_exp];
        
        $token = $this->jwt_encode($payload);
        
        $this->user_login_model->set_token($user->user_id, $token);
        $data['token'] = $token;
        $data['id'] = $user->user_id;
        $data['user_details'] = $user;
        $data['restaurant'] = $restaurant;
        return $this->response(['success' => true,'message' => 'Login successfully', 'data' => $data], REST_Controller::HTTP_OK);
    }
    public function logout()
    {
        if ($this->authenticate() !== true) {
            return;
        }

        $this->user_login_model->set_token($this->current_user->user_id, null);
        return $this->response(['status' => true, 'message' => 'Logged out'], REST_Controller::HTTP_OK);
    }
    public function reset_password()
    {
        $input = $this->post();
        
        $this->form_validation->set_data($input);
        $this->form_validation->set_rules('user_id', 'User id', 'required');
        $this->form_validation->set_rules('old_password', 'Old password', 'required|min_length[6]');
        $this->form_validation->set_rules('new_password', 'New password', 'required|min_length[6]');
        if ($this->form_validation->run() === false) {
            
            return $this->response(['status' => false, 'errors' => $this->form_validation->error_array()], REST_Controller::HTTP_BAD_REQUEST);
        }
        
        $user = $this->user_login_model->get_by_id($input['user_id']);
        if (!$user || !$this->verify_password($input['old_password'], $user->user_password)) {

            return $this->response(['status' => false, 'message' => 'Old password not match.'], REST_Controller::HTTP_UNAUTHORIZED);
        }
        $restaurant = $this->user_login_model->get_restaurant_by_id($user->restaurant_id);
        
        $payload = ['uid' => $user->user_id,  'iat' => time(), 'exp' => time() + $this->jwt_exp];
        
        $token = $this->jwt_encode($payload);
        
        $this->user_login_model->set_token($user->user_id, $token);
        $data['token'] = $token;
        $data['id'] = $user->user_id;
        $data['user_details'] = $user;
        $data['restaurant'] = $restaurant;
        return $this->response(['success' => true,'message' => 'Login successfully', 'data' => $data], REST_Controller::HTTP_OK);
    }
}