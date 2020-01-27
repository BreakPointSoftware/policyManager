<?php
namespace App\Utility\TagExtractor;

//
//  Tags class to ensure type safety when passing around Tags
//  Might be considered overkill, but as Tags are used in far reaching find and replace
//  functionality, building them into a class allow for extendibility and typechecking

class PackedTag
{
    private $_tagPrefix;
    private $_tagRaw;
    private $_replacement;
    private $_allowedPrefixes; 

    public function __construct(string $aTagRaw, string $aReplacement =null) {
        $this->_allowedPrefixes = array( '#', '@', '$', '%', '£', '&'); //need to be moved into the prefix?
        $this->setTagPair($aTagRaw,$aReplacement);
    }

    protected function sanitize(string $aRawTag) {
        
        $sanitizedTag = trim($aRawTag);
        $sanitizedTag = strtoupper($sanitizedTag);
        return $sanitizedTag; 
    }
    
    public function setTagPair(string $aTagRaw, string $aReplacement = null) {
        $sanitizedTagRaw = $this->sanitize($aTagRaw);
        $tempPrefix = $this->extractPrefix($sanitizedTagRaw);
        if ($tempPrefix != null) {
            $this->_tagRaw = $sanitizedTagRaw;
            $this->_replacement = $aReplacement;
            $this->_tagPrefix = $tempPrefix;
        }
    }
    
    public function setReplacement(string $aReplacement) {
        $this->_replacement = $aReplacement;
    }

    public function isPrefixPresent (string $aTaggedString ) 
    {    
        return $this->extractPrefix($aTaggedString) != null ? true : false;
    }

    public function extractPrefix (string $aTaggedString )  
    {
        if(strlen($aTaggedString) > 0)
        {
            $prefix = new TagPrefix($aTaggedString[0]);
            return $this->isAllowed($prefix) ? $prefix : null; 
        }
    }

    public function isAllowed (TagPrefix $aPrefix) :bool 
    {
        return in_array($aPrefix->get(),$this->_allowedPrefixes, true);    
    }

    public function getTag() {
        return $this->_tagRaw;
    }
    
    public function getReplacement() {
        return $this->_replacement;
    }

    public function getPrefix() {
        return $this->_tagPrefix;
    }

    public function isEscapeNeeded() {
            return $this->_tagPrefix->isEscapeNeeded();
    }
}
?>