<?php 

/**
 * Class Config Parser Test
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
class ParserTest extends PHPUnit_Framework_TestCase{
	
  /**
  * Just check if the YourClass has no syntax error 
  *
  * This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
  * any typo before you even use this library in a real project.
  *
  */
  public function testIsThereAnySyntaxError() {
	$this->assertTrue(is_object(\EWC\Config\Parser::getInstance()));
  }
  
  /**
  * Just check if the YourClass has no syntax error 
  *
  * This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
  * any typo before you even use this library in a real project.
  *
  */
  public function testLoad() {
	$var = \EWC\Config\Parser::load(dirname(__FILE__) . "/config/valid_source_structure.conf", \EWC\Config\Parser::TYPE_CONF);
	$this->assertTrue($var->getParserType() == \EWC\Config\Parser::TYPE_CONF);
	unset($var);
  }
  
}