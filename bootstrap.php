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

// registering daemon
CliStart\Cli::daemon($daemon);

// config the base application
include APP_ROOT . "/app-config.dist.php";

// declare the cs run dir
CliStart\Cli::runDir(APP_ROOT . "/cs-data/run");
CliStart\Cli::runArchivesDir(APP_ROOT . "/cs-data/run-archives");





//*******************************************************
//  BEFORE DAEMONIZING WE CHECK IF ALL REQUIREMENT ARE OK
//*******************************************************

// check if command exists
if(!CliStart\Cli::hasCommand($daemon->getCommandName())){
    // TODO : end and log
    die("Call to undefined command : '" . $daemon->getCommandName() . "'\n");
}

// get the COMMANDE DECLARATION
$commandeDeclaration = CliStart\Cli::getCommandDeclaration($daemon->getCommandName());

// validate the args
if(!$commandeDeclaration->validateArgs($daemon->getCommandArgs())){
    // TODO try/catch
    // TODO : end and log
    die("Arguments are not valid \n");
}


// Check if class/method exist
$className = $commandeDeclaration->getCommandClass();
$methodName = $commandeDeclaration->getCommandMethod();

if(!class_exists($className)){
    die("Class '$className' doesn't exist");
}
if(!is_subclass_of($className,"CliStart\Command")){
    die("Class '$className' must extend 'CliStart\Command'");
}




//**************************************************
// all basical requirements are ok, now DAEMON JOB !
//**************************************************

   
// check if command is runnable
if(!CliStart\Cli::commandIsRunnable($commandeDeclaration)){
    die("Command is not runnable");
}

if(!CliStart\Cli::saveDaemon()){
    die("Cant Daemonize");
}

register_shutdown_function(function(){
    CliStart\Cli::stopDaemon();
});




// ALL IS OK LET'S RUN ! 
$commandExecutable = new $className();
$commandExecutable->setArgs($daemon->getCommandArgs());
$commandExecutable->setDaemon($daemon);
$commandExecutable->setCommandDeclaration($commandeDeclaration);

$commandExecutable->$methodName();