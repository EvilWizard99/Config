<?php 

namespace EWC\Config\Tests\Exceptions;

use PHPUnit_Framework_TestCase;
use EWC\Config\Exceptions\ConfigException;
use EWC\Config\Parser;

/**
 * Corresponding Test Class for \EWC\Config\Exceptions\ConfigException
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
class ConfigExceptionTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * Just check if the ReflectorException has no syntax error.
	 */
	public function testIsThereAnySyntaxError() {
		$var = new ConfigException("Test");
		$this->assertTrue(is_object($var));
		unset($var);
	}
	
	/**
	 * Make sure the config section already loaded throws expected exception.
	 * 
	 * @expectedException \EWC\Config\Exceptions\ConfigException
	 * @expectedExceptionMessageRegExp /Config setion already loaded\./
	 * @expectedExceptionCode \EWC\Config\Exceptions\ConfigException::CONFIG_SECTION_ALREADY_LOADED
	 */
	public function testConfigSectionAlreadyloadedThrowsExceptionWithSectionAlreadyLoaded() {
		throw ConfigException::withSectionAlreadyLoaded();
	}
	
	/**
	 * Make sure config failed to load throws expected exception.
	 * 
	 * @expectedException \EWC\Config\Exceptions\ConfigException
	 * @expectedExceptionMessageRegExp /Failed to load config source file \[.+\] using \[.+\] parser\./
	 * @expectedExceptionCode \EWC\Config\Exceptions\ConfigException::INVALID_CONFIG_SOURCE
	 */
	public function testConfigFailedToLoadThrowsExceptionWithfailedToLoadConfigSource() {
		throw ConfigException::withFailedToLoadConfigSource(__FILE__, Parser::TYPE_YAML);
	}
	
	/**
	 * Make sure config failed to parse throws expected exception.
	 * 
	 * @expectedException \EWC\Config\Exceptions\ConfigException
	 * @expectedExceptionMessageRegExp /Failed to parse config source file \[.+\] using \[.+\] parser\./
	 * @expectedExceptionCode \EWC\Config\Exceptions\ConfigException::INVALID_CONFIG_SOURCE
	 */
	public function testConfigFailedToParseThrowsExceptionWithFailedToParseConfigSource() {
		throw ConfigException::withFailedToParseConfigSource(__FILE__, Parser::TYPE_YAML);
	}
	
	/**
	 * Make unknown parser type throws expected exception.
	 * 
	 * @expectedException \EWC\Config\Exceptions\ConfigException
	 * @expectedExceptionMessageRegExp /Unknown parser type \[.+\]\./
	 * @expectedExceptionCode \EWC\Config\Exceptions\ConfigException::UNKNOWN_PARSER_TYPE
	 */
	public function testUnknownParserTypeThrowsExceptionWithUnknownParserType() {
		throw ConfigException::withUnknownParserType("Unknown Parser Name");
	}
  
}