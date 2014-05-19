<?php
/**
 * @copyright (c) Soufiane GHZAL <sghzal@gmail.com>
 * view LICENSE file for licese informations
 */



namespace CliStart\DataAdapter;


use CliStart\CommandDeclaration;
use CliStart\Daemon;
use CliStart\DataAdapter;

class JsonFile implements DataAdapter{

    protected $runDir;

    /**
     * @param mixed $runDir
     */
    public function setRunDir($runDir)
    {
        $this->runDir = $runDir;
    }

    /**
     * @return mixed
     */
    public function getRunDir()
    {
        return $this->runDir;
    }



    public function getCommandFilePattern(CommandDeclaration $command){
        return $this->getRunDir($command) . "/*.csrun.json";
    }


    public function getCommandRunDir(CommandDeclaration $command){
        return $this->getRunDir() . "/" . "command-" . $command->getName();
    }





    /*******************************
     =         IMPLEMENTS          *
     ============================= */

    /**
     * @param CommandDeclaration $command
     * @return int
     */
    public function countRunningInstances(CommandDeclaration $command)
    {
        $pattern = $this->getCommandFilePattern($command);
        return count(glob($pattern));
    }



    public function createRunner(CommandDeclaration $command,Daemon $daemon)
    {
        $runDir = $this->getCommandRunDir($command);
        if(!file_exists($runDir)){
            if(!mkdir($runDir, 0777, true)){
                return false;
            }
        }

        $fileName = $daemon->getCsId() . ".csrun.json";

        if(file_put_contents($runDir . "/" . $fileName,$daemon->jsonize()) === false){
            return false;
        }

        return true;
    }

    public function deleteRunner(CommandDeclaration $command, Daemon $daemon)
    {
        $runDir = $this->getCommandRunDir($command);
        $fileName = $daemon->getCsId() . ".csrun.json";
        unlink($runDir . "/" . $fileName);
    }


    public function getRunnerData($name)
    {
        // TODO: Implement getRunnerData() method.
    }

    public function setRunnerDataArray($name, $value)
    {
        // TODO: Implement setRunnerDataArray() method.
    }


    public function setRunnerData($name, $value)
    {
        // TODO: Implement setRunnerData() method.
    }

    public function getRunner($pid)
    {
        // TODO: Implement getRunner() method.
    }




}