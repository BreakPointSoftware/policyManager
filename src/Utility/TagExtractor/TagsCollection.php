<?php
namespace App\Utility\TagExtractor;

use Cake\Core\Exception\Exception;

use IteratorAggregate;
use App\Utility\Helpers\BasicArrayIterator;
use Countable;

//Helper class for extracting Tags and replacing them
//Tags are assumed to start with prefix and be mixed case
//they will allways be pushed to upper case for matching and storage 

//Consider returning the string with upper case tags in place for cleanses 
class TagsCollection implements IteratorAggregate, Countable
{
    protected $_storedTags;

    public function __construct()
    {
        $this->_storedTags = array();   
    }

    public function add(PackedTag $aTagPacked) {
        if(array_key_exists($aTagPacked->getTag(),$this->_storedTags)) {
            if ($aTagPacked->getReplacement() != null) {
                $this->_storedTags[$aTagPacked->getTag()]->setReplacement($aTagPacked->getReplacement()); 
            }
        } else {
            $this->_storedTags[$aTagPacked->getTag()] = $aTagPacked;
        }

    }

    public function merge(TagsCollection $aPackedTagsToMerge) {
        foreach ($aPackedTagsToMerge as $tag) {
            //Add or update tags in the current collection
            $this->add($tag);
        }
    } 
    public function count() : int
    {
        return count($this->_storedTags);
    }

    public function getIterator()
    {
        return new BasicArrayIterator($this->_storedTags);
    }
    
    
    

    

}

/* #not reference for error handling, something to look into later
class MissingWidgetException extends Exception
{
    // Context data is interpolated into this format string.
    protected $_messageTemplate = 'Seems that %s is missing.';

    // You can set a default exception code as well.
    protected $_defaultCode = 404;
}

throw new MissingWidgetException(['widget' => 'Pointy']);

}
*/
?>