<?php

namespace EWC\Config\Parsers;

use EWC\Config\Parser;

/**
 * Class CLI
 * 
 * Parse Command Line Interface named pair arguments parser.
 *
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 * 
 * @uses	AParser as a base for parsing config.
 * @uses	Parser For constants definition.
 */
class CLI extends AParser {
	
	/**
	 * CLIArgumentsParser constructor.
	 * 
	 * @param	String The full path to the config file to parse.
	 */
	protected function __construct($config_file) {
		parent::__construct($config_file);
		// override the config file with the command line arguments used
		$this->config_source_file = $_SERVER['argv'];
	}
	
	/**
	 * Get the config source parser type.
	 * 
	 * @return	String The config source parser type.
	 */
	public static function getParserType() { return Parser::TYPE_CLI; }
	
	/**
	 * Parse the loaded config source for values.
	 * 
	 * @throws	ConfigParserException If the config can not be parsed.
	 */
	protected function parseConfigSource() {
		// check that the script was invoked with arguments
        if(is_array($this->config_source_file)) {
			// loop through the arguments and extract the values
            foreach ($this->config_source_file as $argument) {
				$this->parseArgumentValue($argument);
            }
        }
    }
	
	/**
	 * Parse the argument key pair value.
	 */
	protected function parseArgumentValue($argument) {
		if(preg_match("#--[a-zA-Z0-9]*=.*#", $argument)) {
		// matches --option-name=option value
		// argument has a value assigned to be used
			// split the argument into "name" = "value" pairs
			$argument_key_pair = preg_split("/[=]{1}/", $argument);
			$argument_value = '';
			// remove the switch string characters from the argument name
			$argument_name = preg_replace("#--#", '', $argument_key_pair[0]);
			// stritch the rest of the string value back together if needed
			for($i = 1; $i < count($argument_key_pair); $i++ ) {
				$argument_value .= $argument_key_pair[$i];
			}
			$this->parsed_config[$argument_name] = $argument_value;
		} elseif(preg_match("#-[a-zA-Z0-9]#", $argument)) {
		// matches -option-name
		// argument is a boolean flag switch
			$argument_name = preg_replace("#-#", '', $argument, 1);
			$this->parsed_config[$argument_name] = TRUE;
		}
	}
	
}
