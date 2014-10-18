<?php
/**
 * @copyright (c) Soufiane GHZAL <sghzal@gmail.com>
 * view LICENSE file for licese informations
 */



namespace CliStart;


interface DataAdapter {

    public function createRunner(CommandDeclaration $command,$csid);
    public function getRunnerData($csid,$name);
    public function setRunnerData($csid,$name,$value);
    public function setRunnerDataArray($csid,$dataArray);
    public function getRunner($csid);
    public function deleteRunner($csid);
    public function countRunningInstances(CommandDeclaration $commandName);
    public function findRunningInstances(CommandDeclaration $commandName);

}