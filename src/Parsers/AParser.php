<?php

namespace EWC\Config\Parsers;

use EWC\Config\Interfaces\IConfigParser;
use EWC\Config\ConfigWrapper;
use EWC\Config\Exceptions\ConfigException;
use EWC\Config\Exceptions\ConfigParserException;

/**
 * Abstract Class AParser
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 * 
 * @uses	IConfigParser To define method / object signatures.
 * @uses	ConfigWrapper To model the individual parsed config values.
 * @uses	ConfigException For constants definition.
 * @uses	ConfigParserException Catches and throws named exceptions.
 */
abstract class AParser implements IConfigParser {
	
	/**
	 * @var	String The full path to the config file being parsed.
	 */
	protected $config_source_file;
	
	/**
	 * @var	String The full path to the folder of the config file being parsed.
	 */
	protected $config_file_path;
	
	/**
	 * @var	Array The parsed config source as an associative config key pair values.
	 */
	protected $parsed_config = [];
	
	/**
	 * AParser constructor.
	 * 
	 * @param	String The full path to the config file to parse.
	 */
	protected function __construct($config_file) {
		// set the config source file to load and parse
		$this->config_source_file = $config_file;
		// add the config file path so included config files can be relative
		$this->config_file_path = dirname($this->config_source_file);
	}
	
	/**
	 * Load the specified config source.
	 * 
	 * @param	String $config_file The config filename to load and parse.
	 * @return	ConfigWrapper The loaded config as in interactively wrapped object.
	 * @throws	ConfigParserException If the config does not contain valid config.
	 * @throws	ConfigException If the source is not an array or a valid JSON String.
	 */
	public static function loadConfig($config_file) {
		// create a new parsing instance for the config source
		$parser = new static($config_file);
		// parse the source
		$parser->parseConfigSource();
		// return the parsed config as an interactive object
		return ConfigWrapper::create($parser->parsed_config, $parser->config_source_file, static::getParserType());
	}
	
	/**
	 * Parse the loaded config source for values.
	 * 
	 * @throws	ConfigParserException If the config can not be parsed.
	 */
	abstract protected function parseConfigSource();	
}
