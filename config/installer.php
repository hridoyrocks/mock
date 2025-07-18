<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Installer Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains all the configuration options for the installer.
    |
    */

    'minimum_php_version' => '8.2.0',

    'required_extensions' => [
        'bcmath',
        'ctype',
        'curl',
        'dom',
        'fileinfo',
        'json',
        'mbstring',
        'openssl',
        'pdo',
        'pdo_mysql',
        'tokenizer',
        'xml',
        'zip',
        'gd',
    ],

    'folders' => [
        'storage/app' => '775',
        'storage/framework' => '775',
        'storage/logs' => '775',
        'bootstrap/cache' => '775',
        'public/uploads' => '775',
    ],

    'installer_lock_file' => 'installed',

];
