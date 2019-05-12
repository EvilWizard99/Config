<?php 

use EWC\Config\Parser;
use EWC\Config\Parsers\Conf;
use EWC\Config\ConfigWrapper;

/**
 * Corresponding Test Class for \EWC\Config\Parsers\Conf
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
class ConfTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * Just check if the BaSH Conf style Config Parser has no syntax error 
	 */
	public function testIsThereAnySyntaxError() {
		$this->assertEquals(Parser::TYPE_CONF, Conf::getParserType(), "YAML Config parser type mismatch.");
	}
	
	/**
	 * Make sure the BaSH Conf style Config parser loads the actual BaSH Conf style Config
	 */
	public function testLoadConfConfigFile() {
		// this config file uses the CONF "IncludeConfig" directive to dynamically include other config files
		$var = Conf::loadConfig(dirname(__FILE__) . "/../config/valid_source_structure.conf");
		$this->assertInstanceOf(ConfigWrapper::class, $var, "Expected an instance of ConfigWrapper");
		$this->assertEquals("Evil_Wizard", $var->get("json.string"), "JSON Config name string mismatch");
		$this->assertCount(3, $var->get("json.array"), "JSON Config Array length mismatch");
		$this->assertEquals("Evil_Wizard", $var->get("yml.string"), "YAML Config string mismatch");
		$this->assertCount(3, $var->get("yml.array"), "YAML Config Array length mismatch");
		$this->assertEquals("Evil_Wizard", $var->get("php.string"), "PHP Config string mismatch");
		$this->assertCount(3, $var->get("php.array"), "PHP Config Array length mismatch");
		unset($var);
	}
	
	/**
	 * Make sure config failed to parse throws expected exception.
	 * 
	 * @expectedException \EWC\Config\Exceptions\ConfigException
	 */
	public function testConfigFailedToParseThrowsExceptionWithFailedToParseConfigSource() {
		Conf::loadConfig(dirname(__FILE__) . "/../config/invalid_source_structure.conf");
	}
	
	/**
	 * Make sure config failed to load throws expected exception.
	 * 
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testConfigFailedToLoadThrowsExceptionWithfailedToLoadConfigSource() {
		Conf::loadConfig(dirname(__FILE__) . "/../config/missing_source.conf");
	}
  
}