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
                    'status' => false,
                    'message' => 'Forbidden'
                ], REST_Controller::HTTP_FORBIDDEN);
            }

            $u = $this->user_login_model->get_by_id($id);
            if (!$u) {
                return $this->response([
                    'status' => false,
                    'message' => 'Not found'
                ], REST_Controller::HTTP_NOT_FOUND);
            }

            unset($u->user_password);
            return $this->response(['success' => true, 'message' => 'register succesfullly' ,'status' => true, 'data' => $u], REST_Controller::HTTP_OK);
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

        return $this->response(['success' => true, 'message' => 'register succesfullly','status' => true, 'data' => $users], REST_Controller::HTTP_OK);
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
            'status' => true,
            'user_id' => $id
        ], REST_Controller::HTTP_CREATED);
    }

    public function index_put($id = null)
    {
        if ($this->authenticate() !== true) return;
        if (!$id) {
            return $this->response([
                'status' => false,
                'message' => 'Missing id'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }

        if ($this->current_user->id != $id ) {
            return $this->response([
                'status' => false,
                'message' => 'Forbidden'
            ], REST_Controller::HTTP_FORBIDDEN);
        }

        if (!$this->validate('user_update')) return;

        $ok = $this->user_login_model->update_user($id, $this->put());

        return $this->response([
            'status' => true,
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
            'status' => true,
            'message' => 'Deactivated'
        ], REST_Controller::HTTP_OK);
    }

    public function forgot_password_post()
{
    $email = $this->post('email');
    $new_password = $this->post('password');

    if (empty($email) || empty($new_password)) {
        return $this->response([
            'status' => false,
            'message' => 'Email and new password are required.'
        ], REST_Controller::HTTP_BAD_REQUEST);
    }

    // Get user
    $user = $this->user_login_model->get_by_email($email);

    if (!$user) {
        return $this->response([
            'status' => false,
            'message' => 'Email not found.'
        ], REST_Controller::HTTP_NOT_FOUND);
    }

    // Update password
    $updated = $this->user_login_model->update_password_by_email($email, $new_password);

    if ($updated) {
        return $this->response([
            'status' => true,
            'message' => 'Password updated successfully.'
        ], REST_Controller::HTTP_OK);
    } else {
        return $this->response([
            'status' => false,
            'message' => 'Failed to update password.'
        ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
    }
}



}

?>
