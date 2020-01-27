<?php

namespace App\Test\TestCase\Utility\TagExtractor;

use App\Utility\TagExtractor\TagExtractor;
use Cake\TestSuite\TestCase;

//Helper class for extracting Tags and replacing them
//Tags are assumed to start with prefix and be mixed case
//they will allways be pushed to upper case for matching and storage 

//Consider returning the string with upper case tags in place for cleaness 
class TagExtractorTest extends TestCase
{
    protected  $sampleText;
    protected  $sampleRawTag;
    protected  $samplePrefix;
    
    function setUp():void {

        //Setup sample test for tests
        $this->sampleText = 'This is a string with #aTag testing 123.';
        $this->sampleRawTag = '#ATAG';      // remember all tags come back as caps
        $this->samplePrefix = '#';
    }

    function testextractTags() {

        $capturedTags = TagExtractor::extractTags($this->samplePrefix, $this->sampleText);
        
        $this->assertIsArray($capturedTags);
        
        // #Note Grab the first element, based on sample data this should be 
        // equal to sampleRawTag
        $tagCaptured = $capturedTags[0];
        $this->assertTextEquals($this->sampleRawTag,$tagCaptured);


        
        
    }
}
