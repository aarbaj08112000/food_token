<?php
defined('BASEPATH') or exit('No direct script access allowed');

#------------ Login -----------------------------
$route['default_controller'] = 'LogonDashboard/login';
// $route['login'] = 'LogonDashboard/login';
/* login & forgot password */
$route['login'] = 'user/login/index';
$route['forgot_password/(:any)/(:any)'] = 'user/login/forgot_password/$1/$2';
$route['logout'] = 'user/login/logout';
$route['login1'] = 'login_old';

/* admin */
$route['sitemap'] = 'user/login/site_map';
$route['user_list'] = 'user1/user_login/start';
$route['group_master'] = 'user/user/groupMaster';
$route['group_menu'] = 'user/user/groupMenu';

$route['group_menu'] = 'user/user/groupMenu';


// /*   api execute */
// $route['WS'] = "wsengine/api_execute/wscontroller";
// $route['WS/(:any)'] = "wsengine/api_execute/wscontroller/$1";
// $route['WS/(:any)/(:any)'] = "wsengine/api_execute/wscontroller/$1/$2";
// $route['WS/(:any)/(:any)/(:any)/(:any)'] = "wsengine/api_execute/wscontroller/$1/$2";
$route['WS/(.*)'] = "wsengine/api_execute/wscontroller/$1";

$GLOBALS['is_ws'] = false;
if (($this->uri->segments[1] == "WS" )) {
$GLOBALS['is_ws'] = true;
}