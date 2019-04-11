<?php

use EWC\Commons\Traits\TErrors;
use EWC\Commons\Utilities\ErrorLogData;
use EWC\Config\Parser;
use EWC\Config\Exceptions\ConfigException;
use Exception;

/**
 * Class Config Parser Test
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 * 
 * @uses	TErrors The errors Traits functionality.
 * @uses	ErrorLogData To wrap data into a compatible format to be logged to file.
 * @uses	QueryFactory To test the functionality of the parsers.
 * @uses	ConfigException Catches named exceptions.
 * @uses	Exception For adding named exceptions for test results.
 */
class ConfigParserTest {
	
	/**
	 * @var	Array Associative collection of test results.
	 */
	protected $results = [];
	
	/**
	 * @var	String The full path to this files location.
	 */
	protected $this_location;
	
	/**
	 * Include the TErrors traits.
	 * 
	 * Adds the following methods for public access.
	 * 
	 * getLastError()
	 * getErrors()
	 * 
	 * Adds the following methods for internal access.
	 * 
	 * addError($error, $trigger=FALSE)
	 * logException(Exception $ex)
	 */
	use TErrors;
	
	/**
	 * ConfigParserTest constructor.
	 */
	public function __construct() {
		// get the location of this file to base the locations of config files to test
		$this->this_location = dirname(__FILE__);
	}
	
	/**
	 * Get the results of the tests run.
	 * 
	 * @param	String $result Optional test result group to get.
	 * @return	Mixed The specified test group results or all the results.
	 */
	public function getResults($result=NULL) { 
		if(is_null($result)) { return $this->results; }
		if(array_key_exists($result, $this->results)) {
			return $this->results[$result];
		}
	}
	
	public function runSuite($suite) {
		switch(strtoupper($suite)) {
			case Parser::TYPE_CONF :
				
			break;
			case Parser::TYPE_YAML :
				
			break;
		}
	}
	
	/**
	 * Test the loading and parsing of valid YAML structure.
	 * 
	 * @return	Boolean TRUE if the test passed.
	 */
	public function validYAML() {
		// set the location of the config file to test with
		$valid_source = "{$this->this_location}/config/valid_source_structure.ym";
		// test the valid config structure loading and parsing
		return $this->runConfigParserPass($valid_source, Parser::TYPE_YAML, "valid_yaml_source");
	}
	
	/**
	 * Test the loading and parsing of valid YAML structure.
	 * 
	 * @return	Boolean TRUE if the test passed.
	 */
	public function invalidYAML() {
		// set the location of the config file to test with
		$invalid_source = "{$this->this_location}/config/invalid_source_structure.yml";
		// test the invalid structure error handling
		return $this->runConfigParserFail($invalid_source, Parser::TYPE_YAML, "invalid_yaml_source");
	}
	
	/**
	 * Test the loading and parsing of valid YAML structure.
	 * 
	 * @return	Boolean TRUE if the test passed.
	 */
	public function missingYAML() {
		// set the location of the config file to test with
		$missing_source = "{$this->this_location}/config/missing.yml";
		// test the missing file error handling
		return $this->runConfigParserFail($missing_source, Parser::TYPE_YAML, "missing_yaml_source");
	}
	
	/**
	 * Test the loading and parsing of valid BaSH Conf structure.
	 * 
	 * @return	Boolean TRUE if the test passed.
	 */
	public function validConf() {
		// set the location of the config file to test with
		$valid_source = "{$this->this_location}/config/valid_source_structure.conf";
		// test the valid config structure loading and parsing
		return $this->runConfigParserPass($valid_source, Parser::TYPE_CONF, "valid_conf_source");
	}
	
	/**
	 * Test the loading and parsing of valid YAML structure.
	 * 
	 * @return	Boolean TRUE if the test passed.
	 */
	public function missingConf() {
		// set the location of the config file to test with
		$missing_source = "{$this->this_location}/config/missing.conf";
		// test the missing file error handling
		return $this->runConfigParserFail($missing_source, Parser::TYPE_CONF, "missing_conf_source");
	}
	
	protected function runConfigParserPass($file, $parser_type, $test_name) {
		try {
			// attempt load and parse the valid config source
			$config = Parser::load($file, $parser_type);
			// succeded in parsing the valid config, this means it's working and passes the test
			$this->results[$test_name] = [
				"status"	=> "PASS",
				"source"	=> $file,
				"parser"	=> $parser_type,
				"parsed"	=> $config
			];
			return TRUE;
		} catch (ConfigException $ex) {
		// unable to parse the valid config, this means it's broken and fails the test
			$this->results[$test_name] = [
				"status"	=> "FAIL",
				"source"	=> "invalid source [{$file}]",
				"parser"	=> $parser_type,
				"exception"	=> $ex
			];
			$this->addError("invalid source [{$file}]");
			$this->addException($ex, $test_name);
			return FALSE;
		}
	}
	
	protected function runConfigParserFail($file, $parser_type, $test_name) {
		try {
			// attempt load and parse the valid config source
			$config = Parser::load($file, $parser_type);
			// succeded in parsing the config, this means it's broken and fails the test
			$this->results[$test_name] = [
				"status"	=> "FAIL",
				"source"	=> $file,
				"parser"	=> $parser_type,
				"parsed"	=> $config
			];
			$this->addError($config);
			return FALSE;
		} catch (ConfigException $ex) {
		// unable to parse the config, this means it's working and passes the test
			// succeded in parsing the valid config, this means it's working and passes the test
			$this->results[$test_name] = [
				"status"	=> "PASS",
				"source"	=> "invalid source [{$file}]",
				"parser"	=> $parser_type,
				"exception"	=> $ex
			];
			return TRUE;
		}
	}
	
	/**
	 * Add an exception trace for test result errors.
	 * 
	 * @param	Exception $ex The Exception to be logged.
	 * @param	String $error_name A name to identify what the error data is / related to.
	 */
	protected function addException(Exception $ex, $error_name) {
		$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
		// create the exception log data
		$ex_data = [
			"Exception: {$ex->getMessage()}",
			"Code: {$ex->getCode()}",
			"File: {$ex->getFile()}",
			"Line: {$ex->getLine()}",
			"Called from: {$backtrace[1]["function"]}()",
			"Trace:\n" . $ex->getTraceAsString()
		];
		// log the exception data
		$this->addError(new ErrorLogData($error_name, $ex_data));
	}
	
}
