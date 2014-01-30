<?php

namespace CliStart\Argument;

/**
 * Boolean
 *
 * @author sghzal
 */
class Boolean extends \CliStart\Argument{
    
    public function __construct($name) {
        parent::__construct($name);
        $this->default = false;
    }
    
    protected function _validate($rawValue) {
        
        if(!is_bool($rawValue))
            return false;
        
        return true;
    }
    
}