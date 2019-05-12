<?php 

use EWC\Config\Parser;
use EWC\Config\Parsers\YAML;
use EWC\Config\ConfigWrapper;

/**
 * Corresponding Test Class for \EWC\Config\Parsers\YAML
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
class YAMLTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * Just check if the YAML Parser has no syntax error 
	 */
	public function testIsThereAnySyntaxError() {
		$this->assertEquals(Parser::TYPE_YAML, YAML::getParserType(), "YAML Config parser type mismatch.");
	}
	
	/**
	 * Make sure the YAML parser loads the actual YAML config
	 */
	public function testLoadYAMLConfigFile() {
		$var = YAML::loadConfig(dirname(__FILE__) . "/../config/valid_source_structure.yml");
		$this->assertInstanceOf(ConfigWrapper::class, $var, "Expected an instance of ConfigWrapper");
		$this->assertEquals("Evil_Wizard", $var->get("yml.string"), "YAML string mismatch");
		$this->assertCount(3, $var->get("yml.array"), "YAML Array length mismatch");
		unset($var);
	}
  
}