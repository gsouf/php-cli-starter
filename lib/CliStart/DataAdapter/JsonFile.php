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


    public function getCommandFile(CommandDeclaration $command, $csid)
    {
        $runDir = $this->getCommandRunDir($command);
        $fileName = $csid . ".csrun.json";
        return $runDir . "/" . $fileName;
    }

    public function getRunnerFile($csid){
        $filePatern = $this->getRunDir() . "/command-*/$csid.csrun.json";
        $file = glob($filePatern);

        if(count($file) == 1){
            return $file[0];
        }

        return false;
    }





    /*******************************
     =         IMPLEMENTS          *
     ============================= */

    /**
     * @param CommandDeclaration $command
     * @return int
     */
    public function countRunningInstances(CommandDeclaration $command){
        $pattern = $this->getCommandFilePattern($command);
        return count(glob($pattern));
    }



    public function createRunner(CommandDeclaration $command,$csid){
        $runDir = $this->getCommandRunDir($command);
        if(!file_exists($runDir)){
            if(!mkdir($runDir, 0777, true)){
                return false;
            }
        }

        if(file_put_contents($this->getCommandFile($command,$csid),"{}") === false){
            return false;
        }

        return true;
    }

    public function deleteRunner($csid){
        unlink($this->getRunnerFile($csid));
    }


    public function getRunnerData($csid , $name){
        //file_get_contents();
    }

    public function setRunnerDataArray($csid , $newData){
        $filename = $this->getRunnerFile($csid);

        if(!$filename)
            return false;

        $actualData = json_decode(file_get_contents($filename),true);

        if(null === $actualData)
            return false;

        $finalData = array_merge($actualData,$newData);

        file_put_contents($filename,json_encode($finalData));

        return true;

    }


    public function setRunnerData($csid , $name, $value){
        $filename = $this->getRunnerFile($csid);

        if(!$filename)
            return false;

        $actualData = json_decode(file_get_contents($filename),true);

        if(null === $actualData)
            return false;

        $actualData[$name] = $value;

        file_put_contents($filename,json_encode($actualData));

        return true;
    }

    public function getRunner($csid){
        // TODO: Implement getRunner() method.
    }
}