<?php

namespace CliStart;

/**
 * CommandDeclaration
 *
 * @author sghzal
 */
class CommandDeclaration {
    
    protected $commandMethod;
    protected $commandClass;
    protected $name;
    protected $maxInstances;

    /**
     * @var Argument[]
     */
    protected $args;
    
    public function __construct($name,$commandClass,$commandMethod) {
        $this->args = array();
        $this->name = $name;
        $this->maxInstances  = 1;
        $this->commandMethod = $commandMethod;
        $this->commandClass  = $commandClass;
    }
    
    public function validateArgs($argv){
        
        foreach ($argv as $k=>$v){
            if(!$this->hasArg($k)){
                throw new \Exception("Argument : '$k' is unknown");
            }
        }
        
        foreach ($this->args as $arg){
            if(!$arg->validate($argv)){
                return false;
            }
        }
        
        return true;
    }
    
    public function getMaxInstances() {
        return $this->maxInstances;
    }

    public function setMaxInstances($maxInstances) {
        $this->maxInstances = $maxInstances;
    }


    public function getName() {
        return $this->name;
    }

        
    public function getCommandClass() {
        return $this->commandClass;
    }

    public function setCommandClass($commandClass) {
        $this->commandClass = $commandClass;
    }

    public function getCommandMethod() {
        return $this->commandMethod;
    }

    public function setCommandMethod($commandMethod) {
        $this->commandMethod = $commandMethod;
    }

    public function getArgs() {
        return $this->args;
    }

    /**
     * @param Argument
     */
    public function getArg($name){
        return $this->args[$name];
    }

    public function hasArg($name){
        return isset($this->args[$name]);
    }
        
    public function addArg(Argument $arg) {
        $this->args[$arg->getName()] = $arg;
    }


    
}