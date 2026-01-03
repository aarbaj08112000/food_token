<?php
require APPPATH.'libraries/REST_Controller.php';
class Api_response extends REST_Controller{

  public function __construct(){
    parent::__construct();
  }

  public function response_return($return_arr = [],$server_error = ""){
    $this->response($return_arr, $server_error);
    return;
  }

}
?>
