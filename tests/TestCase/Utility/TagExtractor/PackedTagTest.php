<?php 
namespace App\Test\TestCase\Utility\TagExtractor;

use App\Utility\TagExtractor\PackedTag;
use App\Utility\TagExtractor\TagPrefix;

use Cake\TestSuite\TestCase;
use App\Test\Utility\TestHelpers\HiddenMethodInvokerTrait;

class PackedTagTest extends TestCase {

    use HiddenMethodInvokerTrait;

    public function setUp(): void
    {
        
    }

    public function testsetTagPair() 
    {
        $packedTag = new PackedTag('#TestTag1','Replacement1');
        $this->assertEquals(strtoupper('#TestTag1'),$packedTag->getTag(),'Failed to construct correctly');
        $packedTag->setTagPair('#TestTag2','Replacement2');
        $this->assertEquals(strtoupper('#TestTag2'),$packedTag->getTag(),'Failed to  setTagPair correctly'); 
        $packedTag->setTagPair('#TestTag3',null);
        $this->assertEquals(strtoupper('#TestTag3'),$packedTag->getTag(),'fail to setTagPair with Null replacement');
    }

    public function testIsPrefixPresent() {
        $packedTag = new PackedTag('#Test');
        $stringToTest1 = '#WithPrefix';
        $stringToTest2 = 'WithoutPrfix'; // testing malformed tags to see if its rejected
        $stringToTest3 = '&edgeCase';
        $stringToTest4 = '"edgeCase';
        
        $this->assertEquals(true,$packedTag->isPrefixPresent ( $stringToTest1 ), 'Did not correctly eastablish if a prefix was present');
        $this->assertEquals(false,$packedTag->isPrefixPresent ( $stringToTest2 ), 'Did not correctly eastablish if a prefix was present');
        $this->assertEquals(true,$packedTag->isPrefixPresent ( $stringToTest3 ), 'Did not correctly eastablish if a prefix was present');
        //We Reject Packed tags without prefixs from the allowed list
        $this->assertEquals(false,$packedTag->isPrefixPresent ( $stringToTest4 ), 'Did not correctly eastablish if a prefix was present');

    }


    public function testExtractPrefix() {
        $packedTag = new PackedTag('#Test');
        $stringToTest1 = '#WithPrefix';
        $stringToTest2 = 'WithoutPrfix';
        $stringToTest3 = '&edgeCase';
        $stringToTest4 = '"edgeCase';
        
        //expect #
        $prefixToTest = $packedTag->extractPrefix ( $stringToTest1 );
        $this->assertEquals('#',$prefixToTest->get());

        //expect null
        $prefixToTest = $packedTag->extractPrefix ( $stringToTest2 );
        $this->assertEquals(null, $prefixToTest);

        //expect &
        $prefixToTest = $packedTag->extractPrefix ( $stringToTest3 );
        $this->assertEquals('&',$prefixToTest->get());

        //We Reject Packed tags without prefixs from the allowed list
        //expect null
        $prefixToTest = $packedTag->extractPrefix ( $stringToTest4 );
        $this->assertEquals(null, $prefixToTest);   
    }

    public function testSetReplacement()
    {
        $packedTag = new PackedTag('#TestTag1','Replacement1');
        $this->assertEquals(('Replacement1'),$packedTag->getReplacement(),'Failed to construct correctly');
        
        $packedTag->setReplacement('Replacement2');
        $this->assertEquals(('Replacement2'),$packedTag->getReplacement(),'set replacement failed');

    }

    public function testIsEscapeNeeded()
    {
        $packedTag = new PackedTag('#TestTag1','Replacement1');
        $this->assertEquals(('Replacement1'),$packedTag->getReplacement(),'Failed to construct correctly');
        
        //All prefixes generated from Tags will allways resolve as True on escaping
        //Need to get a lookup table for escaping
        $this->assertEquals(true,$packedTag->isEscapeNeeded(),'escaping value not returned as expected');

    }

    public function testGetPrefix() {
        $prefixToCompare = new TagPrefix('#', true);

        $packedTag = new PackedTag('#TestTag1','Replacement1');
        $this->assertEquals(('Replacement1'),$packedTag->getReplacement(),'Failed to construct correctly');

        $returnedPrefix = $packedTag->getPrefix();

        $this->assertEquals($prefixToCompare,$returnedPrefix,'Prefix returned if differnt');

    }

    public function testSanitize() {
        //Sanitize is 
        $packedTag = new PackedTag('#TestTag1');
        $params = [' #thisisaTest '];
        $output = $this->invokeMethod($packedTag,'sanitize',$params);
        $this->assertTextEquals('#THISISATEST',$output);
    }
}

?>