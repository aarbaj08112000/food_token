<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array();

#####GENERATED_CONFIG_SETTINGS_START#####

$config["user_login"] = array(
    "title" => "User Login",
    "folder" => "user",
    "method" => "GET_POST",
    "params" => array(
        "email",
        "password"
    )
);
$config["auth"] = array(
    "title" => "Auth",
    "folder" => "user",
    "method" => "GET_POST",
    "params" => array(
        "email",
        "password"
    )
);

$config["order"] = array(
    "title" => "Order",
    "folder" => "orders",
    "method" => "GET_POST",
    "params" => array(
        
    )
);
$config["menu_items"] = array(
    "title" => "Menu",
    "folder" => "menu",
    "method" => "GET_POST",
    "params" => array(
        
    )
);
$config['categories'] = array(
    "title" => "Categories",
    "folder" => "menu",
    "method" => "GET_POST",
    "params" => array(
        
    )
);

/* item CURD */
$config["item_add_update"] = array(
    "title" => "Item Add Update",
    "folder" => "menu",
    "method" => "POST",
    "params" => array(
        
    )
);

$config["get_restaurant_item"] = array(
    "title" => "Get Restaurant Items",
    "folder" => "menu",
    "method" => "POST",
    "params" => array(
        
    )
);


#####GENERATED_CONFIG_SETTINGS_END#####

/* End of file cit_webservices.php */
/* Location: ./application/config/cit_webservices.php */
    
