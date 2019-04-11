<?php 

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
		$this->assertTrue(is_object(\EWC\Config\Parser::getInstance()));
	}
  
	/**
	 * Just check if the Parser has no syntax error 
	 */
	public function testLoad() {
		$var = \EWC\Config\Parser::load(dirname(__FILE__) . "/config/valid_source_structure.conf", \EWC\Config\Parser::TYPE_CONF);
		$this->assertTrue($var->getParserType() == \EWC\Config\Parser::TYPE_CONF);
		unset($var);
	}
  
}