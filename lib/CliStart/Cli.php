<?php


namespace CliStart;

/**
 * Cli
 *
 * @author sghzal
 */
class Cli {
    
    private $commands = array();
    
    /**
     * Daemon of the current script
     * @var \CliStart\Daemon
     */
    private $daemon;

    /**
     * @var DataAdapter
     */
    private $dataAdapter;
    
    public $runLog;
    public $errorLog;

    /**
     * @var Command
     */
    private $runningCommand;

    public function checkRequirement(){

        $cli = $this;
        $daemon = $this->daemon;

        // check if command exists
        if(!$cli->hasCommand($daemon->getCommandName())){
            throw new \Exception("Call to undefined command : '" . $daemon->getCommandName() . "'\n");
        }

        // get the COMMANDE DECLARATION
        $commandDeclaration = $cli->getCommandDeclaration($daemon->getCommandName());

        if(!$commandDeclaration->validateArgs($daemon->getCommandArgs())){
            throw new \Exception("Arguments are not valid \n");
        }

        // Check if class/method exist
        $className = $commandDeclaration->getCommandClass();
        $methodName = $commandDeclaration->getCommandMethod();

        if(!class_exists($className)){
            throw new \Exception("Class '$className' doesn't exist");
        }
        if(!is_subclass_of($className,"CliStart\Command")){
            throw new \Exception("Class '$className' must extend 'CliStart\Command'");
        }


        if(!$this->commandIsRunnable($commandDeclaration)){
            throw new \Exception("Command is not runnable");
        }

        return true;

    }

    /**
     * 
     * @return Daemon
     */
    public function getDaemon() {
        return $this->daemon;
    }

        
    public function start(){

        $daemon = $this->daemon;

        // get the COMMANDE DECLARATION
        $commandDeclaration = $this->getCommandDeclaration($daemon->getCommandName());

        if(!$this->saveDaemon()){
            throw new \Exception("Cant Daemonize");
        }

        $user = $daemon->getUserName();
        $command = $daemon->getCommandString();
        $commandName = $daemon->getCommandName();
        $this->log($this->runLog, "[start][$user][$command][$commandName]");


        $cli = $this;

        register_shutdown_function(function()use($cli){
            $cli->stop();
        });

        $className = $commandDeclaration->getCommandClass();
        $methodName = $commandDeclaration->getCommandMethod();

        $commandExecutable = new $className();
        /* @var $commandExecutable Command */
        $this->runningCommand = $commandExecutable;
        $commandExecutable->setCli($this);
        $commandExecutable->setArgs($daemon->getCommandArgs());
        $commandExecutable->setDaemon($daemon);
        $commandExecutable->setCommandDeclaration($commandDeclaration);
        $commandExecutable->initStartTime();

        $commandExecutable->$methodName();



    }

    /**
     * used internally to stop the script properly. Do not call directly
     */
    public function stop(){
        if($this->runningCommand){

            $runningTime = $this->runningCommand->getRunningTime();

            $this->log($this->runLog, "[stop] running time : $runningTime");
            $this->stopDaemon();
            $this->runningCommand = null;
        }
    }

    public function commandIsRunnable(CommandDeclaration $command){
        
        // check if maxInstance is reached
        if($command->getMaxInstances()<0)
            return false; // todo : no instance allowed
        if($command->getMaxInstances() > 0){
            $runningInstances = $this->dataAdapter->countRunningInstances($command);
            if($runningInstances >= $command->getMaxInstances())
                return false;
        }
        
        
        return true;
        
    }

    /**
     * @param \CliStart\DataAdapter $dataAdapter
     */
    public function setDataAdapter($dataAdapter)
    {
        $this->dataAdapter = $dataAdapter;
    }

    /**
     * @return \CliStart\DataAdapter
     */
    public function getDataAdapter()
    {
        return $this->dataAdapter;
    }


    
    public function log($file,$message){
        
        
        $date = date("Y-m-d h:i:s");
        $csId = $this->daemon()->getCsId();
        $finalMessage = "[$date][$csId]$message\n";
        
        file_put_contents($file, $finalMessage, FILE_APPEND);
        
    }
    
    public function stopDaemon(){
        $daemon  = $this->daemon;
        $command = $this->getCommandDeclaration($daemon->getCommandName());
        
        $this->dataAdapter->deleteRunner($daemon->getCsId());
    }


    public function saveDaemon(){
        $daemon  = $this->daemon;
        $command = $this->getCommandDeclaration($daemon->getCommandName());



        if(!$this->dataAdapter->createRunner($command,$daemon->getCsId() ) ){
            return false;
        }

        $this->dataAdapter->setRunnerDataArray($daemon->getCsId() , $daemon->getSerializableArray());
        return true;

    }





    





    
    /**
     * @param \CS\Command $command
     */
    public function registerCommand(\CliStart\CommandDeclaration $command){
        $this->commands[$command->getName()] = $command;
    }

    /**
     * Set and/or Get daemon of the current script
     * @param \CliStart\Daemon $daemon
     * @return Daemon
     */
    public function daemon(\CliStart\Daemon $daemon=null){
        if(null !== $daemon)
            $this->daemon = $daemon;
        
        return $this->daemon;
    }
   
    
    public function hasCommand($name){
        return isset($this->commands[$name]);
    }
    
    /**
     * 
     * @param type $name
     * @return CommandDeclaration
     */
    public function getCommandDeclaration($name){
        return $this->commands[$name];
    }
    
    public function getRunningInstancesByCommandByName($name){
        
        $command = $this->getCommandDeclaration($name);
        
        if(!$command)
            return array();
        
        return $this->dataAdapter->findRunningInstances($command);
    }
    
    
    /**
     * create a jsonfile representation of the command
     */
    public function jsonTrace($dir){
        
        if(!is_dir($dir) || !is_writable($dir))
            return false;
        
        $data = array(
            "cliId" => $this->cliId,
            "pid" => $this->pid,
            "startTime" => $this->startTime,
            "commandString" => $this->commandString(),
            "name" => $this->command->getName(),
            "description" => $this->command->getDescription()
        );
        
        $jsonString = json_encode($data);
        
        return file_put_contents($this->_getJsonFilename($dir), $jsonString);
    }
    
    public function deleteTrace($dir){
        unlink($this->_getJsonFilename($dir));
    }
    
    protected function _getJsonFilename($dir){
        return $dir . "/" . $this->cliId . ".json";
    }


    /**
     * get the command string
     * @return type
     */
    public function commandString(){
         return join(" ", $_SERVER["argv"]);
    }

}