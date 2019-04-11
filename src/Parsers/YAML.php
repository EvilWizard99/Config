<?php

namespace EWC\Config\Parsers;

use EWC\Config\Parser;
use EWC\Config\Exceptions\ConfigException;
use EWC\Config\Exceptions\ConfigParserException;

/**
 * Class YAML Config Parser
 * 
 * Parse and load YAML config file into an interactive object.
 *
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 * 
 * @uses	AParser as a base for parsing config.
 * @uses	Parser For constants definition.
 * @uses	ConfigException For constants definition.
 * @uses	ConfigParserException Catches and throws named exceptions.
 */
class YAML extends AParser {
	
	/**
	 * YAML constructor.
	 * 
	 * @param	String The full path to the config file to parse.
	 */
	protected function __construct($config_file) {
		parent::__construct($config_file);
	}
	
	/**
	 * Get the config source parser type.
	 * 
	 * @return	String The config source parser type.
	 */
	public static function getParserType() { return Parser::TYPE_YAML; }
	
	/**
	 * Parse the loaded config source for values.
	 * 
	 * @throws	ConfigParserException If the config can not be parsed.
	 */
	protected function parseConfigSource() {
		// load and parse the config file
		$this->parsed_config = yaml_parse_file($this->config_source_file);
		if(!$this->parsed_config) {
		// invalid YAML content
			throw new ConfigParserException("Invalid YAML source content in [{$this->config_source_file}]", ConfigException::FAILED_TO_LOAD_CONFIG_SOURCE);
		}
	}

}
