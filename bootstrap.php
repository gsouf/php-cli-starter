#!/usr/bin/php
<?php

if(php_sapi_name() != "cli")
    die("This scriptis only CLI callable");

// define root path to the application
define("APP_ROOT" , __DIR__);

// init autoloader
include APP_ROOT . "/vendor/autoload.php";


$daemon = new \CliStart\Daemon();
$daemon->initialize();

