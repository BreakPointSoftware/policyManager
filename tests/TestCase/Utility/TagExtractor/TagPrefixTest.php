<?php

namespace App\Test\TestCase\Utility\TagExtractor;

use App\Utility\TagExtractor\TagPrefix;
use App\Utility\TagExtractor\PackedTag;
use App\Utility\TagExtractor\TagsCollection;
use Cake\TestSuite\TestCase;

//Helper class for extracting Tags and replacing them
//Tags are assumed to start with prefix and be mixed case
//they will allways be pushed to upper case for matching and storage 

//Consider returning the string with upper case tags in place for cleaness 
class TagPrefixTest extends TestCase
{
    public function setUp():void {

    }

    public function testGet() {
        $prefixToTest = new TagPrefix("#", true);
        $this->assertTextEquals('#', $prefixToTest->get(),'Prefix did not construct correctly');
        
        $prefixToTest = new TagPrefix("%", false);
        $this->assertTextEquals('%', $prefixToTest->get(),'Prefix did not construct correctly');
        
        $prefixToTest = new TagPrefix("a", false);
        $this->assertEquals(false, $prefixToTest->get(),'did not reject invalid character');
        
        $prefixToTest = new TagPrefix("aa", false);
        $this->assertEquals(false, $prefixToTest->get(),'did not reject invalid character');
        
    }

    public function testIsEscapeNeeded() {
        $prefixToTest = new TagPrefix("#", true);
        $this->assertEquals(true,$prefixToTest->isEscapeNeeded());

        $prefixToTest = new TagPrefix("%", false);
        $this->assertEquals(false,$prefixToTest->isEscapeNeeded());

        $prefixToTest = new TagPrefix("a", false);
        $this->assertEquals(false,$prefixToTest->isEscapeNeeded());

        $prefixToTest = new TagPrefix("aa", false);
        $this->assertEquals(false,$prefixToTest->isEscapeNeeded());
    }

    public function testIsValid() {
        $prefixToTest = new TagPrefix("#", true);
        $this->assertEquals(true, $prefixToTest->isValid('#'),'prefix did not approve correctly');
        $this->assertEquals(false, $prefixToTest->isValid('a'),'prefix did not reject correctly');
        $this->assertEquals(false, $prefixToTest->isValid('1'),'prefix did not reject correctly');
        $this->assertEquals(false, $prefixToTest->isValid('aa'),'prefix did not reject correctly');
        $this->assertEquals(false, $prefixToTest->isValid('11'),'prefix did not rekect correctly');
        $this->assertEquals(true, $prefixToTest->isValid('~'),'prefix did not approve correctly');
        $this->assertEquals(true, $prefixToTest->isValid('\''),'prefix did not approve correctly');
        $this->assertEquals(true, $prefixToTest->isValid('"'),'prefix did not approve correctly');
    }
}