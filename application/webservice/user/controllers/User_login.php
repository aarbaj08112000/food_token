<?php

class User_login extends My_Api_Controller{

   public function __construct()
    {
        parent::__construct();
         $this->load->model('user_login_model');
    }

    public function index_get()
    {
      
        if ($this->get('id')) {
          
            if ($this->authenticate() !== true) return;

            $id = $this->get('id');

            if ($this->current_user->id != $id ) {
                return $this->response([
                    'success' => 0,
                    'message' => 'Forbidden'
                ], REST_Controller::HTTP_FORBIDDEN);
            }

            $u = $this->user_login_model->get_by_id($id);
            if (!$u) {
                return $this->response([
                    'success' => 0,
                    'message' => 'Not found'
                ], REST_Controller::HTTP_NOT_FOUND);
            }

            unset($u->user_password);
            return $this->response(['success' => 1, 'message' => 'register succesfullly' ,'success' => 1, 'data' => $u], REST_Controller::HTTP_OK);
        }
        if ($this->authenticate() !== true) return;
        
        // if ($this->current_user->role !== 'admin') {
        //     return $this->response([
        //         'status' => false,
        //         'message' => 'Forbidden'
        //     ], REST_Controller::HTTP_FORBIDDEN);
        // }

        $users = $this->db->get('users')->result();
        foreach ($users as &$u) {
            unset($u->user_password);
        }

        return $this->response(['success' => 1, 'message' => 'register succesfullly','success' => 1, 'data' => $users], REST_Controller::HTTP_OK);
    }

    public function index_post()
    {
        if ($this->authenticate() !== true) return;
        // if ($this->current_user->role !== 'admin') {
        //     return $this->response([
        //         'status' => false,
        //         'message' => 'Forbidden'
        //     ], REST_Controller::HTTP_FORBIDDEN);
        // }

        if (!$this->validate('user_create')) return;

        $input = $this->post();
        $id = $this->user_login_model->register($input);

        return $this->response([
            'success' => 1,
            'user_id' => $id
        ], REST_Controller::HTTP_CREATED);
    }

    public function index_put($id = null)
    {
        if ($this->authenticate() !== true) return;
        if (!$id) {
            return $this->response([
                'success' => 0,
                'message' => 'Missing id'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }

        if ($this->current_user->id != $id ) {
            return $this->response([
                'success' => 0,
                'message' => 'Forbidden'
            ], REST_Controller::HTTP_FORBIDDEN);
        }

        if (!$this->validate('user_update')) return;

        $ok = $this->user_login_model->update_user($id, $this->put());

        return $this->response([
            'success' => 1,
            'updated' => $ok
        ], REST_Controller::HTTP_OK);
    }

    public function index_delete($id = null)
    {
        if ($this->authenticate() !== true) return;
        // if ($this->current_user->role !== 'admin') {
        //     return $this->response([
        //         'status' => false,
        //         'message' => 'Forbidden'
        //     ], REST_Controller::HTTP_FORBIDDEN);
        // }

        $this->user_login_model->update_user($id, ['is_active' => 0]);

        return $this->response([
            'success' => 1,
            'message' => 'Deactivated'
        ], REST_Controller::HTTP_OK);
    }

    public function forgot_password()
    {
        $email = $this->post('email');
        $new_password = $this->post('password');

        if (empty($email) || empty($new_password)) {
            return $this->response([
                'success' => 0,
                'message' => 'Email and new password are required.',
                'data' => []
            ], REST_Controller::HTTP_BAD_REQUEST);
        }

        // Get user
        $user = $this->user_login_model->get_by_email($email);

        if (!$user) {
            return $this->response([
                'success' => 0,
                'message' => 'Email not found.',
                'data' => []
            ], REST_Controller::HTTP_NOT_FOUND);
        }

        // Update password
        $updated = $this->user_login_model->update_password_by_email($email, $new_password);

        if ($updated) {
            return $this->response([
                'success' => 1,
                'message' => 'Password updated successfully.',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        } else {
            return $this->response([
                'success' => 0,
                'message' => 'Failed to update password.',
                'data' => [],
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function reset_password()
    {
        $input = $this->post();
        
        $this->form_validation->set_data($input);
        $this->form_validation->set_rules('user_id', 'User id', 'required');
        $this->form_validation->set_rules('old_password', 'Old password', 'required|min_length[6]');
        $this->form_validation->set_rules('new_password', 'New password', 'required|min_length[6]');
        if ($this->form_validation->run() === false) {
            
            return $this->response(['success' => 0, 'errors' => $this->form_validation->error_array(),'data' => []], REST_Controller::HTTP_BAD_REQUEST);
        }
        
        $user = $this->user_login_model->get_by_id($input['user_id']);
        if (!$user || !$this->verify_password($input['old_password'], $user->user_password)) {

            return $this->response(['success' => 1, 'message' => 'Old password does not match.','data' => []], REST_Controller::HTTP_UNAUTHORIZED);
        }
        $new_password = $input['new_password'];
        $updated = $this->user_login_model->update_password_by_id($input['user_id'], $new_password);
        if ($updated) {
            return $this->response([
                'success' => 1,
                'message' => 'Password reset successfully.'
            ], REST_Controller::HTTP_OK);
        } else {
            return $this->response([
                'success' => 0,
                'message' => 'Failed to update password.'
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        $this->user_login_model->set_token($user->user_id, $token);
        $data['token'] = $token;
        $data['id'] = $user->user_id;
        $data['user_details'] = $user;
        $data['restaurant'] = $restaurant;
        return $this->response(['success' => 1,'message' => 'Login successfully', 'data' => $data], REST_Controller::HTTP_OK);
    }



}

?>
