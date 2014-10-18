<?php

namespace CliStart;

/**
 * Command
 *
 * @author sghzal
 */
class Command {
    private $args;
    /**
     *
     * @var Daemon
     */
    private $daemon;
    /**
     *
     * @var CommandDeclaration
     */
    private $commandDeclaration;

    private $startTime;


    private $cli;

    public function initStartTime()
    {
        $this->startTime = microtime(true);
    }

    /**
     * @return mixed
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    public function getRunningTime(){
        return microtime(true) - $this->startTime;
    }


    public function setArgs($args) {
        $this->args = $args;
    }

    public function setDaemon(Daemon $daemon) {
        $this->daemon = $daemon;
    }

    public function setCommandDeclaration(CommandDeclaration $commandDeclaration) {
        $this->commandDeclaration = $commandDeclaration;
    }

        
    public function getArgs(){
        return $this->args;
    }
    
    public function getArgValue($name){
        return $this->commandDeclaration->getArg($name)->getValue($this->args);
    }

    /**
     * @param mixed $cli
     */
    public function setCli($cli)
    {
        $this->cli = $cli;
    }

    /**
     * @return Cli
     */
    public function getCli()
    {
        return $this->cli;
    }

    public function storeOption($key,$value){

        $csid = $this->getCli()->getDaemon()->getCsId();
        
        $options = $this->getCli()->getDataAdapter()->getRunnerData($csid, "command-options");
        
        if(!$options){
            $options = array();
        }
        
        $options[$key] = $value;
        
        $this->getCli()->getDataAdapter()->setRunnerData($csid, "command-options", $options);
    }
    
    public function retrieveOption($key){
        
        $csid = $this->getCli()->getDaemon()->getCsId();
        $options = $this->getCli()->getDataAdapter()->getRunnerData($csid, "command-options");
        
        if(!$options){
            return null;
        }
        
        if(isset($options[$key]))
            return $options[$key];
        
        return null;
        
    }
    
    
}