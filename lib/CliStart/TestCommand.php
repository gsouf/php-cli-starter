<?php

namespace CliStart;

/**
 * CommandTest
 *
 * @author sghzal
 */
class TestCommand extends Command {

    public function test(){
        var_dump($this->getArgValue("e"));
    }
    
}