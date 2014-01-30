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