<?php

namespace CliStart;

/**
 * Argument
 *
 * @author sghzal
 */
class Argument {
    
    protected $name;
    protected $required;
    protected $default;
            
    function __construct($name) {
        $this->name = $name;
        $this->required = false;
        $this->default = null;
    }

    public function setRequired($required = true){
        $this->required = $required;
    }
    
    public function setDefault($default){
        if(!$this->_validate($default))
            throw new Exception ("Default value for argument : '$this->name' is not valid");

        $this->default = $default;
    }

    public function validate($argv){
        if($this->required && !isset($argv[$this->name]))
            throw new \Exception("Argument : '$this->name' is required");
        
        return $this->_validate($this->getValue($argv));
    }
    
    protected function _validate($rawValue){
        return true;
    }
    
    public function getValue($argv){
        if(isset($argv[$this->name]))
            return $this->_filterValue ($argv[$this->name]);
        
        return $this->default;
    }
    
    public function getName() {
        return $this->name;
    }

        
    protected function _filterValue($rawValue){
        return $rawValue;
    }
    
}