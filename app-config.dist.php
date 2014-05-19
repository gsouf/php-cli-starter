<?php

$command = new \CliStart\CommandDeclaration("test","CliStart\TestCommand","test");
$command->setMaxInstances(0);
$cli->registerCommand($command);

$arg = new \CliStart\Argument("e");
$arg->setRequired();

$command->addArg($arg);
