<?php 

use EWC\Config\Parser;
use EWC\Config\Parsers\CLI;
use EWC\Config\ConfigWrapper;

/**
 * Corresponding Test Class for \EWC\Config\Parsers\CLI
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
class CLITest extends PHPUnit_Framework_TestCase {
	
	/**
	 * Just check if the YAML Parser has no syntax error 
	 */
	public function testIsThereAnySyntaxError() {
		$this->assertEquals(Parser::TYPE_CLI, CLI::getParserType(), "Command Line Interface Config parser type mismatch.");
	}
	
	/**
	 * Make sure the YAML parser loads the actual YAML config
	 */
	public function testLoadYAMLConfigFile() {
		$var = CLI::loadConfig(NULL);
		$this->markTestIncomplete("Needs a test image to work with");
		$this->assertInstanceOf(ConfigWrapper::class, $var, "Expected an instance of ConfigWrapper");
		$this->assertEquals("Evil_Wizard", $var->get("yml.string"), "Command Line Interface string mismatch");
		$this->assertCount(3, $var->get("yml.array"), "Command Line Interface Array length mismatch");
		unset($var);
	}
  
}