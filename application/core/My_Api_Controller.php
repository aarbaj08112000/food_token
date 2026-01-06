<?php
defined('BASEPATH') or exit('No direct script access allowed');

// require APPPATH . 'libraries/REST_Controller.php';
class My_Api_Controller extends REST_Controller
{

    protected $current_user = null;
    protected $jwt_secret;
    protected $jwt_algo;
    protected $jwt_exp;

    public function __construct()
    {
        
        parent::__construct();
        $this->load->database();
        $this->load->helper(['security', 'url']);
        $this->load->library('form_validation');
        $this->load->config('jwt');
        $this->jwt_secret = $this->config->item('jwt_secret');
        $this->jwt_algo = $this->config->item('jwt_algo');
        $this->jwt_exp = $this->config->item('jwt_exp');
        $this->load->model('user/user_login_model');
    }

    // Basic JWT functions (no external dependency)
    protected function jwt_encode($payload)
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => $this->jwt_algo]);
        $segments = [];
        $segments[] = rtrim(strtr(base64_encode($header), '+/', '-_'), '=');
        $segments[] = rtrim(strtr(base64_encode(json_encode($payload)), '+/', '-_'), '=');
        $signing_input = implode('.', $segments);
        $signature = hash_hmac('sha256', $signing_input, $this->jwt_secret, true);
        $segments[] = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');
        return implode('.', $segments);
    }

    protected function jwt_decode($jwt)
    {
        $parts = explode('.', $jwt);
        if (count($parts) != 3)
            return null;
        list($headb64, $bodyb64, $cryptob64) = $parts;
        $header = json_decode(base64_decode(strtr($headb64, '-_', '+/')), true);
        $payload = json_decode(base64_decode(strtr($bodyb64, '-_', '+/')), true);
        $sig = base64_decode(strtr($cryptob64, '-_', '+/'));
        $valid = hash_hmac('sha256', $headb64 . '.' . $bodyb64, $this->jwt_secret, true);
        if (!hash_equals($valid, $sig))
            return null;
        // check expiry
        if (isset($payload['exp']) && time() > $payload['exp'])
            return ["success" => -1,"message" => "Token Expired","uid" =>  $payload['uid']];
        return $payload;
    }

    protected function authenticate()
    {
        $auth = $this->input->get_request_header('Authorization', TRUE);
        if (!$auth) {
            $this->response(['status' => false, 'message' => 'Missing Authorization header'], REST_Controller::HTTP_UNAUTHORIZED);
            return false;
        }
        if (stripos($auth, 'Bearer ') === 0) {
            $token = substr($auth, 7);
        } else {
            $this->response(['status' => false, 'message' => 'Invalid Authorization format. Use Bearer <token>'], REST_Controller::HTTP_UNAUTHORIZED);
            return false;
        }
        $payload = $this->jwt_decode($token);
        if (!$payload || !isset($payload['uid'])) {
            $this->response(['status' => false, 'message' => 'Invalid or expired token'], REST_Controller::HTTP_UNAUTHORIZED);
            return false;
        }

        if ($payload['success'] == -1 &&  isset($payload['success']) ) {
            $this->user_login_model->set_token($payload['uid'], null);
            $this->response(['success' => $payload['success'], 'message' => $payload['message']], REST_Controller::HTTP_UNAUTHORIZED);
            return false;
        }
        // load user
        $user = $this->user_login_model->get_by_id($payload['uid']);
        if (!$user) {
            $this->response(['status' => false, 'message' => 'User not found'], REST_Controller::HTTP_UNAUTHORIZED);
            return false;
        }
        $this->current_user = $user;
        return true;
    }

    // Require one or more roles
    protected function require_role($roles = [])
    {
        if (!$this->current_user) {
            $this->response(['status' => false, 'message' => 'Not authenticated'], REST_Controller::HTTP_UNAUTHORIZED);
            return false;
        }
        $r = (array) $roles;
        if (!in_array($this->current_user->role, $r)) {
            $this->response(['status' => false, 'message' => 'Forbidden'], REST_Controller::HTTP_FORBIDDEN);
            return false;
        }
        return true;
    }

    protected function hash_password($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    protected function verify_password($password, $hash)
    {
        return password_verify($password, $hash);
    }

    protected function validate($group)
    {
        // run validation rules defined in config/form_validation.php
        if ($this->form_validation->run($group) === FALSE) {
            $this->response([
                'status' => false,
                'errors' => $this->form_validation->error_array()
            ], REST_Controller::HTTP_BAD_REQUEST);
            return false;
        }
        return true;
    }
    public function upload_image($file_name = "",$folder_path = ""){
        $profileImageData = $_FILES[$file_name]["name"] != "" ? $_FILES[$file_name] : [];
        $config["upload_path"] = $folder_path;
        if (!is_dir(FCPATH .$config["upload_path"])) {
            mkdir(FCPATH . $config["upload_path"], 0777, TRUE);
        }
        $config["allowed_types"] = "jpg|jpeg|png"; 
        $this->load->library("upload", $config);
        $this->upload->initialize($config);
        $upload_error_msg = [];
        $upload_error = 0;
        $image_url = "";
        if (!empty($profileImageData)) {
            if (!$this->upload->do_upload($file_name)) {
                $upload_error_msg = $error = [
                    "error" => $this->upload->display_errors(),
                ];
                $upload_error = 1;
            } else {
                $upload_data = $this->upload->data();
                $image_url = $folder_path.$upload_data['file_name'];
            }
        }
        
        $return_arr = [
            "upload_error" => $upload_error,
            "upload_error_msg" => $upload_error_msg,
            "image_url" => $image_url
        ];

        return $return_arr;
    }
}
