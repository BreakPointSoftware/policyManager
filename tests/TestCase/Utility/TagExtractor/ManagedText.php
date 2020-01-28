<?php
namespace App\Test\TestCase\Utility\TagExtractor;

use App\Utility\TagExtractor\PackedTag;
use App\Utility\TagExtractor\TagsCollection;
use App\Utility\TagExtractor\ManagedText;
use App\Utility\TagExtractor\TagPrefix;
use Cake\TestSuite\TestCase;

class ManagedTextTest extends TestCase {

    protected $textToManage1;
    protected $textToManage2;
    protected $tagsCollection;
    protected $managedText1;
    protected $managedText2;
    protected $managedTextWithNullTags3;
    protected $managedTextWithNullTags4;
    protected $tagPrefix;

    public function setUp():void 
    {
        
        $this->textToManage1 = "This is a #Sample #TEXT to Manage";
        $this->textToManage2 = "This is a #Sample1 #Sample2 Text to Manage";

        $this->tagsCollection = New TagsCollection();
        $this->tagsCollection->add(new PackedTag('#SAMPLE',true));
        $this->tagsCollection->add(new PackedTag('#SAMPLE1',true));
        $this->tagsCollection->add(new PackedTag('#SAMPLE2',true));
        $this->tagsCollection->add(new PackedTag('#TEXT',true));

        $this->managedText1 = new ManagedText($this->textToManage1,$this->tagsCollection);
        $this->managedText2 = new ManagedText($this->textToManage2,$this->tagsCollection);
        $this->managedTextWithNullTags3 = new ManagedText($this->textToManage1);
        $this->managedTextWithNullTags4 = new ManagedText($this->textToManage2);

        $this->tagPrefix = new TagPrefix('#',true);

    }

    /*
    public function testGetAllTags()
    {
        
        //Test the second textToManage1
        $tagsCollectionToCheck = $this->managedTextWithNullTags3->GetAllTags();
        $mycount =0;

        foreach ($tagsCollectionToCheck as $tag) {
            
            if ($mycount == 0) {   
                $this->assertTextEquals('#SAMPLE',$tag->getTag());
            }
            if ($mycount == 1) {      
                $this->assertTextEquals('#TEXT',$tag->getTag());
            }
            $mycount++;
        }

        //Test the second textToManage2
        $tagsCollectionToCheck = $this->managedTextWithNullTags4->GetAllTags();
        $mycount =0;

        foreach ($tagsCollectionToCheck as $tag) {
            if ($mycount == 0) {   
                    $this->assertTextEquals('#SAMPLE1',$tag->getTag());
            }
            if ($mycount == 1) {   
                    $this->assertTextEquals('#SAMPLE2',$tag->getTag());
            }
            $mycount++;
        }
    }
    
/*
    public function testGetAllTags()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testGetPresemtationText()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testSetTags() 
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testSetPrefix() 
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testAddPrefix() 
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
    */

}
