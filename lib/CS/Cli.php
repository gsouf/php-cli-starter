<?php


namespace Cs;

/**
 * Cli
 *
 * @author sghzal
 */
abstract class Cli {
    
    protected static $cliId;
    protected static $startTime;
    protected static $pid;
    protected static $command;

    
    
    public static function start(){
        self::$startTime = microtime(true);
        self::$pid       = getmypid();
        self::$cliId     = self::_generateCliId();
    }
    
    /**
     * @param \CS\Command $command
     */
    public static function registerCommand(\CS\Command $command){
        self::$command    = $command;
    }

    /**
     * generate a cli id intended to uniquely identify this cli script
     * @return type
     */
    protected static function _generateCliId(){
        return self::$pid . '-' . self::$startTime . '-' . rand(0, 9) . rand(0, 9);
    }
    
    public static function getCliId(){
        return self::$cliId;
    }
    public static function getStartTime(){
        return self::$startTime;
    }
    
    
    /**
     * create a jsonfile representation of the command
     */
    public static function jsonTrace($dir){
        
        if(!is_dir($dir) || !is_writable($dir))
            return false;
        
        $data = array(
            "cliId" => self::$cliId,
            "pid" => self::$pid,
            "startTime" => self::$startTime,
            "commandString" => self::commandString(),
            "name" => self::$command->getName(),
            "description" => self::$command->getDescription()
        );
        
        $jsonString = json_encode($data);
        
        return file_put_contents(self::_getJsonFilename($dir), $jsonString);
    }
    
    public static function deleteTrace($dir){
        unlink(self::_getJsonFilename($dir));
    }
    
    protected static function _getJsonFilename($dir){
        return $dir . "/" . self::$cliId . ".json";
    }


    /**
     * get the command string
     * @return type
     */
    public static function commandString(){
         return join(" ", $_SERVER["argv"]);
    }

}