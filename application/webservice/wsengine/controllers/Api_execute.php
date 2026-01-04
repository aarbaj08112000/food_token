<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class api_execute extends MX_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function __construct(){

	    parent::__construct();
	    //load database
		//ini_set('display_errors', 1);
		// ini_set('display_startup_errors', 1);
		// error_reporting(E_ALL);

	  }
	public function wscontroller()
	{
		
		$this->config->load('api_config', TRUE);
		$all_methods = $this->config->item('api_config');
		$api_method_name = $this->uri->segments[2];
		$api_func = $this->uri->segments[3];
		
	// Collect extra segments as parameters
		$extra_params = array_slice($this->uri->segments, 4);
		
		if(array_key_exists($api_method_name, $all_methods)){
			
			
			/* get api method details */
			$method_details = $all_methods[$api_method_name];
			/* create api path */
			
			
			$api_path = $method_details['folder']."/controllers/".ucfirst($api_method_name);
			require APPPATH.'libraries/REST_Controller.php';
			$this->load->module($method_details['folder'] . "/" . $api_method_name);
				// Now you can access the module instance through $this
			// $className = ucfirst($api_method_name); 
	        // $api_instant = new $className(); 
            
			// Dynamically call API function if provided, else call method based on HTTP verb
			$request_method = $this->input->server('REQUEST_METHOD');
			$params = $request_method === 'GET' ? $this->input->get() : $this->input->post();
			// Merge extra URI segments as positional parameters
			$call_params = $extra_params;
			// Optionally, you can also pass $params as the last argument
			if (!empty($params)) {
				$call_params[] = $params;
			}
			if ($api_func && method_exists($this->$api_method_name, $api_func)) {
				// Pass all collected parameters to the API function
				call_user_func_array([$this->$api_method_name, $api_func], $call_params);
			} else {
				// Map HTTP verbs to method names
				$method_map = [
					'GET' => 'index_get',
					'POST' => 'index_post',
					'PUT' => 'index_put',
					'DELETE' => 'index_delete',
					'PATCH' => 'index_patch'
				];
				$method_to_call = $method_map[$request_method] ?? null;
				
				if ($method_to_call && method_exists($this->$api_method_name, $method_to_call)) {
					
					call_user_func_array([$this->$api_method_name, $method_to_call], $call_params);
				} else {
					// Fallback: method not found
					require_once(APPPATH.'webservice/wsengine/controllers/Api_response.php');
					$api_instant = new Api_response();
					$return_arr = [
						"success" => 0,
						"message" => "API method not found.",
						"data" => []
					];
					$server_error = REST_Controller::HTTP_NOT_FOUND;
					$api_instant->response_return($return_arr, $server_error);
				}
			}

		}else{
			require_once(APPPATH.'webservice/wsengine/controllers/Api_response.php');
			// pr($api_method_name,1);
			$api_instant = new Api_response(); 
			$return_arr = array(
		        "success" => 0,
		        "message" => "API code does not exist.",
		    );
		    $return_arr = array(
		        "success" => 0,
		        "message" => "API code does not exist.",
		        "data" => []
		    );
			
		    $server_error = REST_Controller::HTTP_OK;
		    $api_instant->response_return($return_arr,$server_error);
		}
		
		
	}
}
