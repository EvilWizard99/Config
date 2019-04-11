<?php

namespace EWC\Config\Parsers;

use EWC\Config\Parser;
use EWC\Config\Exceptions\ConfigException;
use EWC\Config\Exceptions\ConfigParserException;
use EWC\Commons\Utilities\DataType\Convertor;
use EWC\Commons\Libraries\FileSystem;

/**
 * Class BaSH Conf style Config Parser
 * 
 * Parse and load BaSH Conf style config file into an interactive object.
 *
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 * 
 * @uses	AParser as a base for parsing config.
 * @uses	Parser For constants definition.
 * @uses	ConfigException For constants definition.
 * @uses	ConfigParserException Catches and throws named exceptions.
 * @uses	Convertor To convert the base values into type cast inflated values.
 * @uses	FileSystem To for the existance of included config files.
 */
class Conf extends AParser {
	
	/**
	 * @var	Array The lines of the raw config file being parsed.
	 */
	protected $raw_config;
	
	/**
	 * @var	Boolean Flag to indicate if the current raw config file line is considered a comment.
	 */
	protected $comment_block = FALSE;
	
	/**
	 * @var	Boolean Flag to indicate optional config settings if the module is available.
	 */
	protected $if_module = FALSE;
	
	/**
	 * @var	String The module name to group the optional config under.
	 */
	protected $if_module_group = '';
	
	/**
	 * Conf constructor.
	 * 
	 * @param	String The full path to the config file to parse.
	 */
	protected function __construct($config_file) {
		parent::__construct($config_file);
		// read the file into an array and remove blank/empty lines
		$this->raw_config = file($this->config_source_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	}
	
	/**
	 * Get the config source parser type.
	 * 
	 * @return	String The config source parser type.
	 */
	public static function getParserType() { return Parser::TYPE_CONF; }
	
	/**
	 * Parse the loaded config source for values.
	 * 
	 * @throws	ConfigParserException If the config can not be parsed.
	 */
	protected function parseConfigSource() {
		// loop through the lines of the config and parse the contents
		foreach($this->raw_config as $line_no => $line) {
			if(!$this->isCommentLine($line)) {
			// line is an actual config key value pair
				// parse the line for key and value
				$this->parseConfigLine($line);
			}
		}
	}
	
	/**
	 * Parse the config line for values.
	 * 
	 * @param	String $line The config line to parse.
	 * @throws	ConfigParserException If the config line can not be parsed.
	 */
	protected function parseConfigLine($line) {
		// spit the line into property name and value
		$pairs = preg_split("/[=]{1}/", $line);
		if(count($pairs) > 1) {
		// basic config value setting structure
			// add the config setting
			return $this->addIntoConfig(trim($pairs[0]), Convertor::cast(trim($pairs[1])));
		}
		// split the line on spaces and use a Command Key Value approach
		$parser_command = preg_split("/[\s]+/", $line);
		$command_parts = count($parser_command);
		// get the command name, to specifically parse the line
		$command = trim($parser_command[0]);
		// get the command key, used optionally in some parser commands
		$command_key = ($command_parts > 1) ? trim($parser_command[1]) : '';
		// implode the rest of the array using spaces, this will allow commands with
		// spaces in the value to be loaded correctly
		$command_value = ($command_parts > 2) ? trim(implode(' ', array_slice($parser_command, 2))) : '';
		switch ($command) {
			case "<ifModule":
			// begin a config group section if the module can be loaded & used
				$this->if_module = TRUE;
				// @todo check for and remove any ">" closing bracket of the tag
				$this->if_module_group = $command_key;
				// create an array key to hold the module specific settings
				$this->parsed_config[$this->if_module_group] = [];
				// @todo use reflector to determine if setting are needed
			break;
			case "</ifModule>":
			// close the config group section
				// reset the flag and name for the module
				$this->if_module = FALSE;
				$this->if_module_group = '';
			break;
			case "LoadModule" :
			// load the specified php module for application use
				// @todo see if this is still needed with namespace & autoloading
				//$this->parserCommandLoadModule($command_key, $command_value);
			break;
			case "IncludeConfig" :
			// include an addition config file
				$this->parserCommandIncludeConfig($command_key, $command_value);
			break;
			case "DatabaseSlave":
			// add a database slave connection
				$this->parsed_config["database_slaves"][$command_value] = $command_key;
			break;
			default:
			// assume the property didn't have an '=' sign e.g standard config file support
				// add the config setting
				$this->addIntoConfig($command, "{$command_key} {$command_value}");
			break;
		}
	}
	
	/**
	 * Include a sub config file and parse.
	 * 
	 * @param	String $parser_type The config parser type to load the config.
	 * @param	String $include_file The config file to include.
	 * @throws	ConfigException If the included config file can not be parsed.
	 */
	protected function parserCommandIncludeConfig($parser_type, $include_file) {
		// determine the config file absolute path
		$file = ((substr($include_file, 0, 1) == '.')
					? "{$this->config_file_path}/{$include_file}"
					: $include_file
				);
		// parse the included config file
		$included = Parser::load($file, $parser_type);
		if($this->if_module) {
		// include this config inside the module group section
			$merge_to = &$this->parsed_config[$this->if_module_group];
		} else {
		// include this config at root level of the config
			$merge_to = &$this->parsed_config;
		}
		// merge the values from the included config
		$merge_to = array_merge_recursive($merge_to, $included->toArray());
	}
	
	/**
	 * Load a module from the config.
	 * 
	 * @param	String $module_name The module name to load.
	 * @param	String $module_file The full path to the module source file.
	 * @throws	ConfigParserException If the module can not be loaded.
	 */
	protected function parserCommandLoadModule($module_name, $module_file) {
		// check that the file can be read in and loaded later on demand
		if(!FileSystem::fileExists($module_file)) {
			throw new ConfigParserException("Failed to load module [{$module_name}] from [{$module_file}]", ConfigException::FAILED_TO_LOAD_CONFIG_SOURCE);			
		}
		try {
			// @todo could add an autoload modules class property
			//$this->checkModule($module_name, $module_file);
		} catch(ConfigParserException $ex) {
		// something went wrong loading the module file
			
		}
	}
	
	/**
	 * Add the key value pair setting to the config.
	 * 
	 * @param	String $key The config setting name.
	 * @param	Mixed $value The config setting value.
	 */
	protected function addIntoConfig($key, $value) {
		if($this->if_module) {
		// add the setting inside the module group section
			$config = &$this->parsed_config[$this->if_module_group];
		} else {
		// add the setting at the root level of the config
			$config = &$this->parsed_config;
		}		
		$config[$key] = $value;
	}
	
    /**
    * Check to see if a line from a config file is a comment or part of a comment block.
	* 
    * @param	String $line the config file line to test.
    * @return	Boolean TRUE if the line is considered part of a comment.
    */
	protected function isCommentLine($line) {
		// assume the line is NOT a comment first
		$is_comment = FALSE;
		// check if this is the start of a comment block
		if((preg_match("/^(\/\*)(.*)/", $line) == 1) && !$this->comment_block) {
			// comment block started
			$this->comment_block = TRUE;
			$is_comment = TRUE;
		} else {
			// check if we have been in a comment block and its closing
			if($this->comment_block && (preg_match("/^(*\/)(.*)/", $line) == 1)) {
				// comment block is ending, but is still a comment line though
				$this->comment_block = FALSE;
				$is_comment = TRUE;
			}
		}
		// check if the begining of the line starts with comment characters
		if(!$is_comment && (preg_match("/^(#|;|\/\/)(.*)/", $line) == 1)) {
		// config line is just a comment
			$is_comment = TRUE;
		}
		return $is_comment;
	}

}
