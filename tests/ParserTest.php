<?php 

use EWC\Config\Parser;
use EWC\Config\ConfigWrapper;

/**
 * Corresponding Test Class for \EWC\Config\Parser
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
class ParserTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * Just check if the Parser has no syntax error 
	 */
	public function testIsThereAnySyntaxError() {
		$this->assertTrue(is_object(Parser::getInstance()));
	}
	
	/**
	 * Make sure the parser loads the actual JSON config
	 */
	public function testLoadJSONConfigFile() {
		$var = Parser::load(dirname(__FILE__) . "/config/valid_source_structure.json", Parser::TYPE_JSON);
		$this->assertInstanceOf(ConfigWrapper::class, $var, "Expected an instance of ConfigWrapper");
		$this->assertEquals("Evil_Wizard", $var->get("json.string"), "JSON Config name string mismatch");
		$this->assertCount(3, $var->get("json.array"), "JSON Config Array length mismatch");
		unset($var);
	}
	
	/**
	 * Make sure the parser loads the actual YAML config
	 */
	public function testLoadYAMLConfigFile() {
		$var = Parser::load(dirname(__FILE__) . "/config/valid_source_structure.yml", Parser::TYPE_YAML);
		$this->assertInstanceOf(ConfigWrapper::class, $var, "Expected an instance of ConfigWrapper");
		$this->assertEquals("Evil_Wizard", $var->get("yml.string"), "YAML string mismatch");
		$this->assertCount(3, $var->get("yml.array"), "YAML Array length mismatch");
		unset($var);
	}
	
	/**
	 * Make sure the parser loads the actual PHP config
	 */
	public function testLoadPHPConfigFile() {
		$var = Parser::load(dirname(__FILE__) . "/config/valid_source_structure.php", Parser::TYPE_PHP_ARRAY);
		$this->assertInstanceOf(ConfigWrapper::class, $var, "Expected an instance of ConfigWrapper");
		$this->assertEquals("Evil_Wizard", $var->get("php.string"), "PHP Config string mismatch");
		$this->assertCount(3, $var->get("php.array"), "PHP Config Array length mismatch");
		unset($var);
	}

	/**
	 * Make sure the parser loads the all the actual configs
	 */
	public function testLoadConfFileWithIncludedKnownParserIncludesInConfigFile() {
		// this config file uses the CONF "IncludeConfig" directive to dynamically include other config files
		$var = Parser::load(dirname(__FILE__) . "/config/valid_source_structure.conf", Parser::TYPE_CONF);
		$this->assertInstanceOf(ConfigWrapper::class, $var, "Expected an instance of ConfigWrapper");
		$this->assertEquals("Evil_Wizard", $var->get("json.string"), "JSON Config name string mismatch");
		$this->assertCount(3, $var->get("json.array"), "JSON Config Array length mismatch");
		$this->assertEquals("Evil_Wizard", $var->get("yml.string"), "YAML Config string mismatch");
		$this->assertCount(3, $var->get("yml.array"), "YAML Config Array length mismatch");
		$this->assertEquals("Evil_Wizard", $var->get("php.string"), "PHP Config string mismatch");
		$this->assertCount(3, $var->get("php.array"), "PHP Config Array length mismatch");
		unset($var);
	}
  
}