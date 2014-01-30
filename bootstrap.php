#!/usr/bin/php
<?php

if(php_sapi_name() != "cli")
    die("This scriptis only CLI callable");

// define root path to the application
define("APP_ROOT" , __DIR__);

// init autoloader
include APP_ROOT . "/vendor/autoload.php";

// init daemon
$daemon = new \CliStart\Daemon();
$daemon->initialize();

// parse args
$daemon->parseInputArgs($_SERVER['argv']);

// register daemon
CliStart\Cli::daemon($daemon);

// config the base application
include APP_ROOT . "/app-config.dist.php";

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

$className = $commandeDeclaration->getCommandClass();
$methodName = $commandeDeclaration->getCommandMethod();

if(!class_exists($className)){
    die("Class '$className' doesn't exist");
}
if(!is_subclass_of($className,"CliStart\Command")){
    die("Class '$className' must extend 'CliStart\Command'");
}

$commandExecutable = new $className();
$commandExecutable->setArgs($daemon->getCommandArgs());
$commandExecutable->setDaemon($daemon);
$commandExecutable->setCommandDeclaration($commandeDeclaration);

$commandExecutable->$methodName();