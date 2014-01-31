<?php

namespace CliStart;

/**
 * CommandTest
 *
 * @author sghzal
 */
class TestCommand extends Command {

    public function test(){
        sleep(5);
        var_dump($this->getArgValue("e"));
    }
    
}