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
		$var = Parser::load(Parser::TYPE_JSON, dirname(__FILE__) . "/config/valid_source_structure.json");
		$this->assertInstanceOf(ConfigWrapper::class, $var, "Expected an instance of ConfigWrapper");
		$this->assertEquals("Evil_Wizard", $var->get("json.string"), "JSON Config name string mismatch");
		$this->assertCount(3, $var->get("json.array"), "JSON Config Array length mismatch");
		unset($var);
	}
	
	/**
	 * Make sure the parser loads the actual YAML config
	 */
	public function testLoadYAMLConfigFile() {
		$var = Parser::load(Parser::TYPE_YAML, dirname(__FILE__) . "/config/valid_source_structure.yml");
		$this->assertInstanceOf(ConfigWrapper::class, $var, "Expected an instance of ConfigWrapper");
		$this->assertEquals("Evil_Wizard", $var->get("yml.string"), "YAML string mismatch");
		$this->assertCount(3, $var->get("yml.array"), "YAML Array length mismatch");
		unset($var);
	}
	
	/**
	 * Make sure the parser loads the actual PHP config
	 */
	public function testLoadPHPConfigFile() {
		$var = Parser::load(Parser::TYPE_PHP_ARRAY, dirname(__FILE__) . "/config/valid_source_structure.php");
		$this->assertInstanceOf(ConfigWrapper::class, $var, "Expected an instance of ConfigWrapper");
		$this->assertEquals("Evil_Wizard", $var->get("php.string"), "PHP Config string mismatch");
		$this->assertCount(3, $var->get("php.array"), "PHP Config Array length mismatch");
		unset($var);
	}
	
	/**
	 * Make sure the parser loads the actual PHP config
	 */
	public function testLoadCLIConfigFile() {
		$var = Parser::load(Parser::TYPE_CLI);
		$this->assertInstanceOf(ConfigWrapper::class, $var, "Expected an instance of ConfigWrapper");
//		$this->assertEquals("Evil_Wizard", $var->get("cli-option-value"), "CLI argument Config string mismatch");
//		$this->assertTrue($var->get("cli-option-flag"), "CLI argument Config string mismatch");
		unset($var);
	}

	/**
	 * Make sure the parser loads the all the actual configs
	 */
	public function testLoadConfFileWithIncludedKnownParserIncludesInConfigFile() {
		// this config file uses the CONF "IncludeConfig" directive to dynamically include other config files
		$var = Parser::load(Parser::TYPE_CONF, dirname(__FILE__) . "/config/valid_source_structure.conf");
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
	 * Make ConfigWrapper imports additional config 
	 */
	public function testConfigWrapperImportsConfigCorrectly() {
		$var = Parser::load(Parser::TYPE_YAML, dirname(__FILE__) . "/config/valid_source_structure.yml");
		$this->assertInstanceOf(ConfigWrapper::class, $var, "Expected an instance of ConfigWrapper");
		$this->assertInstanceOf(ConfigWrapper::class, $var, $var->importConfig(Parser::load(Parser::TYPE_JSON, dirname(__FILE__) . "/config/valid_source_structure.json")), "Expected an instance of ConfigWrapper");
		$this->assertEquals("Evil_Wizard", $var->get("yml.string"), "Source YAML Config string mismatch");
		$this->assertCount(3, $var->get("yml.array"), "Source YAML Config Array length mismatch");
		$this->assertEquals("Evil_Wizard", $var->get("json.string"), "Imported JSON Config name string mismatch");
		$this->assertCount(3, $var->get("json.array"), "Imported JSON Config Array length mismatch");
		unset($var);
	}
  
}