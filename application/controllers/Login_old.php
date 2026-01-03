<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login_old extends MY_Controller {
	public function __construct() {
        parent::__construct();
    }
	public function index() {
		pr("ok",1);
		$data['base_url'] = base_url();
		$this->smarty->loadView('login.tpl',$data,'No','No');
	}
	
}

