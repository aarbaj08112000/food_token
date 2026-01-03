<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['jwt_secret'] = 'ReplaceThisWithAStrongRandomSecretKey';
$config['jwt_algo'] = 'HS256';
$config['jwt_exp'] = 3600; // seconds
