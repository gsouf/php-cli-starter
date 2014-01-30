<?php

$command = new \CliStart\CommandDeclaration("test","CliStart\TestCommand","test");
CliStart\Cli::registerCommand($command);

$arg = new \CliStart\Argument("e");
$arg->setRequired();

$command->addArg($arg);
