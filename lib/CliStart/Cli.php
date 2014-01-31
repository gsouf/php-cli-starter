<?php


namespace CliStart;

/**
 * Cli
 *
 * @author sghzal
 */
abstract class Cli {
    
    private static $commands = array();
    
    /**
     * Daemon of the current script
     * @var \CliStart\Daemon
     */
    private static $daemon;
    
    private static $csRunDir;
    
    public static $runLog;
    public static $errorLog;

    public static function commandIsRunnable(CommandDeclaration $command){
        
        // check if maxInstance is reached
        if($command->getMaxInstances()<0)
            return false; // todo : no instance allowed
        if($command->getMaxInstances() > 0){
            $runningInstances = self::countRunningInstances($command);
            if($runningInstances >= $command->getMaxInstances())
                return false;
        }
        
        
        
        // check if time is ok
        // TODO
        
        
        return true;
        
    }
    
    public static function log($file,$message){
        
        
        $date = date("Y-m-d h:i:s");
        $csId = self::daemon()->getCsId();
        $finalMessage = "[$date][$csId]$message\n";
        
        file_put_contents($file, $finalMessage, FILE_APPEND);
        
    }
    
    public static function stopDaemon(){
        $daemon  = self::$daemon;
        $command = self::getCommandDeclaration($daemon->getCommandName());
        
        $runDir = self::getCommandRunDir($command);
        $fileName = $daemon->getCsId() . ".csrun.json";
        unlink($runDir . "/" . $fileName);
    }


    public static function saveDaemon(){
        $daemon  = self::$daemon;
        $command = self::getCommandDeclaration($daemon->getCommandName());
        
        $runDir = self::getCommandRunDir($command);
        if(!file_exists($runDir)){
            if(!mkdir($runDir, 0777, true)){
                return false;
            }
        }
        
        $fileName = $daemon->getCsId() . ".csrun.json";
        
        if(file_put_contents($runDir . "/" . $fileName,$daemon->jsonize()) === false){
            return false;
        }
        
        return true;
    }


    public static function getCommandRunDir(CommandDeclaration $command){
        return self::$csRunDir . "/" . "command-" . $command->getName();
    }
    

    public static function getCommandFilePattern(CommandDeclaration $command){
        return self::getCommandRunDir($command) . "/*.csrun.json";
    }
    
    public static function countRunningInstances(CommandDeclaration $command){
        $pattern = self::getCommandFilePattern($command);
        return count(glob($pattern));
        
    }


    public static function runDir($runDir = null){
        if(null !== $runDir)
            self::$csRunDir = $runDir;
        
        return self::$csRunDir;
    }

    
    /**
     * @param \CS\Command $command
     */
    public static function registerCommand(\CliStart\CommandDeclaration $command){
        self::$commands[$command->getName()] = $command;
    }

    /**
     * Set and/or Get daemon of the current script
     * @param \CliStart\Daemon $daemon
     * @return Daemon
     */
    public static function daemon(\CliStart\Daemon $daemon=null){
        if(null !== $daemon)
            self::$daemon = $daemon;
        
        return self::$daemon;
    }
   
    
    public static function hasCommand($name){
        return isset(self::$commands[$name]);
    }
    
    /**
     * 
     * @param type $name
     * @return CommandDeclaration
     */
    public static function getCommandDeclaration($name){
        return self::$commands[$name];
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