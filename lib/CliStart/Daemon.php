<?php


namespace CliStart;

/**
 * Daemon
 * 
 * A Deamon represents one running script informations
 * 
 * It is aimed to be serialized in the run directory
 *
 * @author sghzal
 */
class Daemon {
    
    protected $pid;
    protected $uid;
    protected $userName;
    protected $gid;
    protected $csid;
    protected $startTime;
    protected $commandString;
    
    /**
     * Initialize this deamon with startTime, commandeString, pid, gid and uid based on the current script
     */
    public function initialize() {
        $this->startTime = microtime(true);
        
        $this->pid = getmypid();
        
        $userData = posix_getpwuid(posix_geteuid());
        $this->gid = $userData["gid"];
        $this->uid = $userData["uid"];
        $this->userName = $userData["name"];
        
        $this->_generateCliId();
        $this->commandString = join(" ", $_SERVER["argv"]);
    }
    
    /**
     * Initilize with the given file path
     * @param string $jsonFilePath path to the csrun.json
     */
    public function initializeFromJsonFile($jsonFilePath){
        $data = file_get_contents($jsonFilePath);
        $this->initializeFromData(json_decode($data));
    }
    
    /**
     * Initialize with the given data
     * @param array $data list of data
     */
    public function initializeFromData($data){
        
        $this->pid = $data["pid"];
        $this->gid = $data["gid"];
        $this->uid = $data["uid"];
        $this->csid = $data["csid"];
        $this->startTime = $data["startTime"];
        $this->commandString = $data["commandString"];
        
    }
    
    public function getPid() {
        return $this->pid;
    }
    
    /**
     * generate a cli id intended to uniquely identify this cli script
     */
    private function _generateCliId(){
        $this->csid = $this->pid . '-' . $this->startTime . '-' . rand(0, 9) . rand(0, 9);
    }

    public function getCsId() {
        return $this->csId;
    }

    public function getStartTime() {
        return $this->startTime;
    }
    

    public function getCommandString() {
        return $this->commandString;
    }


    public function getName() {
        return $this->name;
    }
    
    
    /**
     * @deprecated
     */
    public function setCommandString($commandString) {
        $this->commandString = $commandString;
    }
    
    /**
     * @deprecated
     */
    public function setStartTime($startTime) {
        $this->startTime = $startTime;
    }

    /**
     * @deprecated
     */
    public function setName($name) {
        $this->name = $name;
    }
    
    /**
     * @deprecated
     */
    public function setCsId($csId) {
        $this->csId = $csId;
    }
    
    /**
     * @deprecated
     */
    public function setPid($pid) {
        $this->pid = $pid;
    }

    /**
     * @deprecated
     */
    public function setUid($uid) {
        $this->uid = $uid;
    }

    /**
     * @deprecated
     */
    public function setGid($gid) {
        $this->gid = $gid;
    }

    
}