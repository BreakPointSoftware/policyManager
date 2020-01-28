<?php
namespace App\Utility\TagExtractor;

//
//  Basic prefix type = stores the prefix and flag to 
//  whether it should be escaped
//

class TagPrefix
{
    private $_prefix;
    private $_isEscapingRequired;

    public function __construct(string $aPrefix, bool $aIsEscapingRequired = true)
    {
        $this->_prefix  = $this->isValid($aPrefix) ? $aPrefix : null;
        
        //We are contacting the knowledge of whether escaping is required
        $this->_isEscapingRequired = $aIsEscapingRequired;
        assert($this->_prefix !== null, 'Invalid prefix error, only single character non-alpha-numericals can bue used as prefixes');
        return $this->_prefix;
    }

    //public function getPrefix(): string 
    //{
        //return $this->_prefix;
    //}

    public function get() :?string
    {
        return $this->_prefix;
    }

    public function isEscapeNeeded() : bool 
    {
        return $this->_isEscapingRequired;
    }

    public function isValid(string $aPrefix) : bool
    {
        $regexString = '^[a-zA-Z0-9_]^';
              
        if ( strlen($aPrefix)  == 1) {
            if (!preg_match_all($regexString,$aPrefix)) {
                return true;
            }
        } else {
            return false;
        }
        return false;
    }
}