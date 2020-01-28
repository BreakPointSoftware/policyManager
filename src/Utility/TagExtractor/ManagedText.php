<?php
namespace App\Utility\TagExtractor;

use App\Utility\Helpers\IntegrityEnum;
//
//  Managed Text (Component)
//
//  Helper for holding and managing text with Tags
//
//  Responsible for returning text with and without Tags
//  Performing appropriate replacements
//  Extracting and storing Tags from Text
//


class ManagedText
{
    private $_storedTags;
    private $_storedTagIntegrity;
    private $_sourceText;
    private $_presentationText;
    private $_presentationTextIntegrity;
    private $_prefixedUsed;
    private $_importedPrefixes;
    private $_prefixedUsedIntegrity;
    private $_importedTags;

    public function __construct(string $aTextToManage, TagsCollection $aTags = null)
    {
        $this->_sourceText = $aTextToManage; 
        $this->_presentationTextIntegrity = IntegrityEnum::Invalid;  
        $this->_storedTagIntegrity = IntegrityEnum::Invalid;
        $this->_prefixedUsed = array();
        $this->_prefixedUsedIntegrity = IntegrityEnum::Invalid;
        $this->_importedPrefixes = array();
        $this->_importedTags =  ($aTags == null) ? new TagsCollection() : $aTags;
        $this->_storedTags = ($aTags == null) ? new TagsCollection() : $aTags;        
        $this->cleanTagsInSource();

    }

    public function getTagsWithPrefix(TagPrefix $aPrefix)  
    {
        $arrayOfTagsToReturn = new TagsCollection();

        if($this->_storedTagIntegrity == IntegrityEnum::Invalid) {
            $this->checkAndrebuildPrefixesUsed();
            $this->checkAndRebuildStoredTags();
        }

        if($this->_storedTagIntegrity == IntegrityEnum::Valid) {
            foreach ($this->_storedTags as $tag) {
                if($tag->getPrefix()->get() == $aPrefix->get()) {
                    $arrayOfTagsToReturn->add($tag);
                }
            }
            return  $arrayOfTagsToReturn;
        }
        return null;           
    }

    public function getAllTags() 
    {
        if($this->_storedTagIntegrity == IntegrityEnum::Valid) {
            return $this->_storedTags;
        }
        else {
            $this->checkAndrebuildPrefixesUsed();
            $this->checkAndRebuildStoredTags();
            return $this->_storedTags;
        }
    }

    public function getPresentationText(): string
    {
        $this->checkAndRebuildStoredTags();

        if($this->_presentationTextIntegrity == IntegrityEnum::Invalid) {
            $this->replaceTags();
            //This could be extended to do others things with the presentation text
        }

        if (count($this->_storedTags) > 0 ) {
            if($this->_presentationTextIntegrity == IntegrityEnum::Valid) {
                return $this->_presentationText;
            } else {
                assert($this->_presentationTextIntegrity == IntegrityEnum::Invalid, 'There was a problem extracting tags - check tag data');
            }
        }
        else {
            //There where no tags to replace to return the source string
            $this->_presentationTextIntegrity = IntegrityEnum::Valid;
            $this->_presentationText = $this->_sourceText;
            return $this->_presentationText;

        }
    } 

    public function setTags(TagsCollection $aTags) {
        $this->invalidate();
        $this->_storedTags = $aTags;
    }

    public function addTags(TagsCollection $aTagsCollection) {
        $this->invalidate();
        $this->_storedTags->merge($aTagsCollection);
        
    }

    public function setPrefix(TagPrefix $aTagPrefix) {
        $this->invalidate();
        $this->_importedPrefixes = array();
        $this->mergePrefix($aTagPrefix);
    }

    public function addPrefix(TagPrefix $aTagPrefix) {
        $this->invalidate();
        $this->mergePrefix($aTagPrefix);
    }

    protected function mergePrefix(TagPrefix $aTagPrefix) 
    {
        if(!array_key_exists($aTagPrefix->get(),$this->_importedPrefixes)) {
            $this->_importedPrefixes[$aTagPrefix->get()] = $aTagPrefix;
            if(!array_key_exists($aTagPrefix->get(),$this->_prefixedUsed)) {
                $this->_prefixedUsed[$aTagPrefix->get()] = $aTagPrefix;
            }
        }
    }
    
    protected function checkAndRebuildStoredTags() : bool
    {
        $this->checkAndrebuildPrefixesUsed();
        if ($this->_storedTagIntegrity == IntegrityEnum::Invalid)
        {
            $this->_storedTags = new TagsCollection();
            if($this->_importedTags != null) {
                foreach ($this->_importedTags as $importedTag) {
                    $this->_storedTags->add($importedTag);
                }
            }

            foreach ($this->_prefixedUsed as $prefix) {
                $this->extractTags($prefix);
            }
            $this->_storedTagIntegrity = IntegrityEnum::Valid;
        }
        return true;
    }


    protected function checkAndrebuildPrefixesUsed() : bool
    {
        if($this->_presentationTextIntegrity == IntegrityEnum::Invalid) {
            $this->_prefixedUsed = array();
            if($this->_importedPrefixes != null) {
                foreach ($this->_importedPrefixes as $importedPrefix) {
                    if(!array_key_exists($importedPrefix->get(),$this->_prefixedUsed)) {
                        $this->_prefixedUsed[$importedPrefix->get()] = $importedPrefix;
                    }
                }
            }
            if($this->_storedTags!=null) {
                foreach ($this->_storedTags as $tag) {
                    if(!array_key_exists($tag->getPrefix()->get(), $this->_prefixedUsed)) {
                        $this->_prefixedUsed[$tag->getPrefix()->get()] = $tag->getPrefix();
                    }
                }
            }
        }
        return true;
    }

    protected function extractTags(TagPrefix $aPrefix) 
    {
       
        $this->_storedTagIntegrity = IntegrityEnum::Invalid;

        //Because the TagPrefix Type will marshal validity by returning Null for malformed Tags
        if ($aPrefix !== null) {
    
            //Escape the prefix chat
            $aPrefix->isEscapeNeeded() ? $prefixToUse = "\\" . $aPrefix->get() : $prefixToUse = $aPrefix->get();
            
            $regexString = '^\s*('. $prefixToUse .'[a-zA-Z0-9_]+)\s*^';
            $capturedTags = new TagsCollection();

            //We can take in a prefix of any type, so we can extend it to @ and % tags if needed
            preg_match_all($regexString,$this->_sourceText,$matches,PREG_PATTERN_ORDER);
            
            if ($matches) {

                foreach($matches as $group)
                {
                    foreach ($group as $match) {
                        
                        // we sanitize the match as part of the packing
                        // maybe problematic
                        $capturedTags->add(new PackedTag($match));
                    }
                }       
                $this->_storedTags->merge($capturedTags);        
                $this->_storedTagIntegrity = IntegrityEnum::Valid;
            }  
        } 

    }

    
    //Replace tags in source text with actual strings needed
    protected function replaceTags()
    {
        
        $this->checkAndRebuildStoredTags();
        
        if ($this->_storedTagIntegrity == IntegrityEnum::Valid)
        {
            $this->_presentationText  = $this->_sourceText;
            foreach($this->_storedTags as $tagValue) {

                //Escape the prefix chat
                if ($tagValue->isEscapeNeeded()) {
                    $tag = "\\" . $tagValue->getTag();
                } else {
                    $tag = $tagValue->getTag();
                }
                
                //Using I to set case insensitive
                $regexString = '^('. $tag .')^i';     
                $this->_presentationText = preg_replace($regexString, $tagValue->getReplacement(), $this->_presentationText);
                $this->_presentationTextIntegrity = IntegrityEnum::Valid;
            } 
        } else {
            //Logically we have hit a runtime problem if we hit here as th
            assert($this->_storedTagIntegrity == IntegrityEnum::Invalid, 'failed to replace the tags - check stored tags for integrity');
        }
    }

    //Clean the source text so all tags are displayed in upper case
    protected function cleanTagsInSource() {
        
        $this->checkAndRebuildStoredTags();
        
        foreach($this->_storedTags as $tagToReplace) {

            //Escape the prefix chat
            if ($tagToReplace->isEscapeNeeded()) {
                $tag = "\\" . $tagToReplace->getTag();
            } else {
                $tag = $tagToReplace->getTag();
            }
            
            $regexString = '^'. $tag .'^i';            //Using I to set case insensitive
            $this->_sourceText = preg_replace($regexString, $tagToReplace->getTag(), $this->_sourceText);
        }
            
    }

    protected function invalidate() {
        $this->_storedTagIntegrity = IntegrityEnum::Invalid;
        $this->_presentationTextIntegrity = IntegrityEnum::Invalid;
        $this->_prefixedUsedIntegrity = IntegrityEnum::Invalid;
    }
}