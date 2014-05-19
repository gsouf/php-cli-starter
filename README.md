CLI-STARTER
===========

|ALPHA TESTING| => all feature are not implemented yet

Cli starter is a way to start your php scripts in the background.
You keep full controller over them, you know how they run, what step they run...

You can interact with them while they are running, stop them at any moment.

Cli starter also offers you an easy way to configure and create your commands.



Usage
=====

Cli Starter is delivered as a library. You can install it via composer : ``sneakybobito/cli-starter``

You will need a boostrap file. For instance :

````php

    #!/usr/bin/php
    <?php

    if(php_sapi_name() != "cli")
        die("This scriptis only CLI callable");


    //**********************
    //  INIT THE APPLICATION
    //**********************

    // define root path to the application
    define("APP_ROOT" , __DIR__);

    // init autoloader
    include APP_ROOT . "/vendor/autoload.php";

    // init daemon
    $daemon = new \CliStart\Daemon();
    $daemon->initialize();

    // parse args
    $daemon->parseInputArgs($_SERVER['argv']);


    $cli = new \CliStart\Cli();

    // registering daemon
    $cli->daemon($daemon);

    // config the base application
    include APP_ROOT . "/app-config.dist.php";

    // declare the dir
    $cli->runDir(APP_ROOT . "/cs-data/run");
    $cli->runLog = APP_ROOT . "/cs-data/log/run.log";
    $cli->errorLog = APP_ROOT . "/cs-data/log/error.log";



    //*******************************************************
    //  BEFORE DAEMONIZING WE CHECK IF ALL REQUIREMENT ARE OK
    //*******************************************************

    try{
        if(!$cli->checkRequirement()){
            die("error");
        }
    }catch (\Exception $e){
        die($e->getMessage());
    }



    //**************************************************
    // all basical requirements are ok, now DAEMON JOB !
    //**************************************************

    try{
        $cli->start();
    }catch (\Exception $e){
        die($e->getMessage());
    }



````



Incoming Features
==================

* Steps
* Checkpoint
* mongo adapter
* interaction while running

* monitor run via cli or web 
* start script from web
* stop script from cli or web
* default test quiet and help mods
* delay between script run
* configurable cron mod


Reserved Args
=============

* quiet
* help
* conf-file

Thanks
======

Thanks to pwfisher for it's command line script that was usefull to me : https://github.com/pwfisher/CommandLine.php