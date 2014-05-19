<?php
/**
 * @copyright (c) Soufiane GHZAL <sghzal@gmail.com>
 * view LICENSE file for licese informations
 */



namespace CliStart;


interface DataAdapter {

    public function createRunner(CommandDeclaration $command,Daemon $daemon);
    public function getRunnerData($name);
    public function setRunnerData($name,$value);
    public function setRunnerDataArray($name,$value);
    public function getRunner($pid);
    public function deleteRunner(CommandDeclaration $commandDeclaration,Daemon $daemon);
    public function countRunningInstances(CommandDeclaration $commandName);

}