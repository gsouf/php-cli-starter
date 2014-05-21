<?php

namespace CliStart;


class Caller {

    protected $binFilePath;

    /**
     * @param $binFilePath path to the bootstrap of your cli start application
     */
    function __construct($binFilePath)
    {
        $this->binFilePath = $binFilePath;
    }


    public function launchBackground($commandName,$params = array()){

        $commandString = $this->binFilePath . " " . $commandName;

        foreach($params as $k=>$v){
            $commandString .= " --$k $v";
        }

        $fullCommand = "nohup sh -c 'php $commandString' 2>&1 1>/dev/null &";
    }

}