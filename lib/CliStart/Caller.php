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


    public function launchBackground($commandName,$params = array(),$outputFile = "/dev/null"){

        $commandString = $this->binFilePath . " " . $commandName;

        if(is_array($params))
            foreach($params as $k=>$v){
                $commandString .= " --$k $v";
            }

        $fullCommand = "nohup php $commandString > $outputFile 2>&1 &";

        exec($fullCommand);
    }

}
