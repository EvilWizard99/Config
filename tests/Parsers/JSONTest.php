<?php 

use EWC\Config\Parser;
use EWC\Config\Parsers\JSON;
use EWC\Config\ConfigWrapper;

/**
 * Corresponding Test Class for \EWC\Config\Parsers\JSON
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
class JSONTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * Just check if the JSON Parser has no syntax error 
	 */
	public function testIsThereAnySyntaxError() {
		$this->assertEquals(Parser::TYPE_JSON, JSON::getParserType(), "JSON Config parser type mismatch.");
	}
	
	/**
	 * Make sure the JSON parser loads the actual JSON config
	 */
	public function testLoadJSONConfigFile() {
		$var = JSON::loadConfig(dirname(__FILE__) . "/../config/valid_source_structure.json");
		$this->assertInstanceOf(ConfigWrapper::class, $var, "Expected an instance of ConfigWrapper");
		$this->assertEquals("Evil_Wizard", $var->get("json.string"), "JSON string mismatch");
		$this->assertCount(3, $var->get("json.array"), "JSON Array length mismatch");
		unset($var);
	}
	
	/**
	 * Make sure config failed to parse throws expected exception.
	 * 
	 * @expectedException \EWC\Config\Exceptions\ConfigException
	 */
	public function testConfigFailedToParseThrowsExceptionWithFailedToParseConfigSource() {
		JSON::loadConfig(dirname(__FILE__) . "/../config/invalid_source_structure.json");
	}
	
	/**
	 * Make sure config failed to load throws expected exception.
	 * 
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testConfigFailedToLoadThrowsExceptionWithfailedToLoadConfigSource() {
		JSON::loadConfig(dirname(__FILE__) . "/../config/missing_source.json");
	}
  
}