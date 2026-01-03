<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config = [
    'login' => [
        [
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'required'
        ],
        [
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'required|min_length[6]'
        ]
    ],

    'register' => [
        [
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'required|valid_email'
        ],
        [
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'required|min_length[8]'
        ]
    ],
    'user_create' => [
        [
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'required|valid_email|is_unique[users.email]'
        ],
        [
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'required|min_length[8]'
        ],
        [
            'field' => 'role',
            'label' => 'Role',
            'rules' => 'required|in_list[user,admin]'
        ]
    ],

    'user_update' => [
        [
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'valid_email'
        ],
        [
            'field' => 'role',
            'label' => 'Role',
            'rules' => 'in_list[user,admin]'
        ]
    ]

];
