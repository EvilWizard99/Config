<?php

namespace EWC\Config\Parsers;

use EWC\Config\Parser;
use EWC\Config\Exceptions\ConfigException;
use EWC\Config\Exceptions\ConfigParserException;

/**
 * Class PHP Array Config Parser
 * 
 * Parse and load PHP Array config file into an interactive object.
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
class JSON extends AParser {
	
	/**
	 * JSON constructor.
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
	public static function getParserType() { return Parser::TYPE_JSON; }
	
	/**
	 * Parse the loaded config source for values.
	 * 
	 * @throws	ConfigParserException If the config can not be parsed.
	 */
	protected function parseConfigSource() {
		// load and parse the config file
		$this->parsed_config = file_get_contents($this->config_source_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		if(!$this->parsed_config) {
		// invalid PHP Array content
			throw new ConfigParserException("Failed to load file content from [{$this->config_source_file}]", ConfigException::FAILED_TO_LOAD_CONFIG_SOURCE);
		}
	}

}
