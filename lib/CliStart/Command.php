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
    
    
}