<?php

namespace App\Test\TestCase\Utility\TagExtractor;

use App\Utility\TagExtractor\PackedTag;
use App\Utility\TagExtractor\TagsCollection;
use Cake\TestSuite\TestCase;

//Helper class for extracting Tags and replacing them
//Tags are assumed to start with prefix and be mixed case
//they will allways be pushed to upper case for matching and storage 

//Consider returning the string with upper case tags in place for cleaness 
class TagsCollectionTest extends TestCase
{
    //protected  $sampleText;
    //protected  $sampleRawTag;
    //protected  $samplePrefix;

    protected $packedTag1;
    protected $packedTag2;
    
    public function setUp():void {

        //Setup sample test for tests
        //$this->sampleText = 'This is a string with #aTag testing 123.';
        //$this->sampleRawTag = '#ATAG';      // remember all tags come back as caps
        //$this->samplePrefix = '#';

        $this->packedTag1 = new PackedTag('#TagString1','Replacement1');
        $this->packedTag2 = new PackedTag('#TagString2','Replacement2');
        $this->packedTag3 = new PackedTag('#TagString2','Replacement3');
        
        //$sourceText = '#TagString1 #TagString2';


    }

    public function testAdd() {

        $TagsCollection = new TagsCollection;
        $TagsCollection->add($this->packedTag1);
        $this->assertCount(1,$TagsCollection, 'Tag did not add correctly, expecting 1 Tag in collection');
        
        $TagsCollection->add($this->packedTag2);
        $this->assertCount(2,$TagsCollection, 'Tag did not add correctly, expecting 2 Tags in collection');
        
        //Re Adding first tag to check that it updates rather than adds
        $TagsCollection->add($this->packedTag1);
        $this->assertCount(2,$TagsCollection, 'Tag did not add correctly, expecting 1 Tag in collection');
        
    }

    public function testTraverse() {

        $TagsCollection = new TagsCollection;
        $TagsCollection->add($this->packedTag1);
        $this->assertCount(1,$TagsCollection, 'Tag did not add correctly, expecting 1 Tag in collection');
        
        $TagsCollection->add($this->packedTag2);
        $this->assertCount(2,$TagsCollection, 'Tag did not add correctly, expecting 2 Tags in collection');

        $elementCount = 0;

        //Note the Tags collection bounces Tags to uppercase, so miex case Tag should be returned
        //as uppercase
        foreach ($TagsCollection as $packedTag) {
            if ($elementCount ==0) {
                $this->assertEquals( strtoupper('#TagString1'),$packedTag->getTag());
            }
            if ($elementCount ==1) {
                $this->assertEquals( strtoupper('#TagString2'),$packedTag->getTag(),'The Last Tag added wasn\'t correctly merged');
            }
            $elementCount++;
        }
        $this->assertEquals(2,$elementCount,'Two values are in the collection only got 1');
    }

    public function testMerge() {
        $TagsCollection = new TagsCollection;
        $TagsCollection->add($this->packedTag1);
        $TagsCollection->add($this->packedTag2);
        $TagsCollection->add($this->packedTag3);

        $elementCount = 0;

        foreach ($TagsCollection as $packedTag) {
            if ($elementCount ==0) {
                $this->assertEquals( 'Replacement1',$packedTag->getReplacement());
            }
            if ($elementCount ==1) {
                $this->assertEquals( 'Replacement3',$packedTag->getReplacement());
            }
            $elementCount++;
        }
        $this->assertEquals(2,$elementCount,'Two values are in the collection only got 1');
     
    }
}
