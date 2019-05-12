<?php 

namespace EWC\Config\Tests\Exceptions;

use PHPUnit_Framework_TestCase;
use EWC\Config\Exceptions\ConfigParserException;

/**
 * Corresponding Test Class for \EWC\Config\Exceptions\ConfigParserException
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
class ConfigParserExceptionTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * Just check if the ConfigParserException has no syntax error.
	 */
	public function testIsThereAnySyntaxError() {
		$var = new ConfigParserException("Test");
		$this->assertTrue(is_object($var));
		unset($var);
	}
  
}