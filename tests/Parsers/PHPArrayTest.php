<?php 

use EWC\Config\Parser;
use EWC\Config\Parsers\PHPArray;
use EWC\Config\ConfigWrapper;

/**
 * Corresponding Test Class for \EWC\Config\Parsers\PHPArray
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
class PHPArrayTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * Just check if the PHPArray Parser has no syntax error 
	 */
	public function testIsThereAnySyntaxError() {
		$this->assertEquals(Parser::TYPE_PHP_ARRAY, PHPArray::getParserType(), "PHP Array Config parser type mismatch.");
	}
	
	/**
	 * Make sure the PHPArray parser loads the actual PHP Array config
	 */
	public function testLoadPHPArrayConfigFile() {
		$var = PHPArray::loadConfig(dirname(__FILE__) . "/../config/valid_source_structure.php");
		$this->assertInstanceOf(ConfigWrapper::class, $var, "Expected an instance of ConfigWrapper");
		$this->assertEquals("Evil_Wizard", $var->get("php.string"), "PHP Array string mismatch");
		$this->assertCount(3, $var->get("php.array"), "PHP Array Array length mismatch");
		unset($var);
	}
	
	/**
	 * Make sure config failed to parse throws expected exception.
	 * 
	 * @expectedException \EWC\Config\Exceptions\ConfigException
	 */
	public function testConfigFailedToParseThrowsExceptionWithFailedToParseConfigSource() {
		PHPArray::loadConfig(dirname(__FILE__) . "/../config/invalid_source_structure.php");
	}
	
	/**
	 * Make sure config failed to load throws expected exception.
	 * 
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testConfigFailedToLoadThrowsExceptionWithfailedToLoadConfigSource() {
		PHPArray::loadConfig(dirname(__FILE__) . "/../config/missing_source.php");
	}
  
}