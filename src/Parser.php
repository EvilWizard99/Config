<?php

namespace EWC\Config;

use EWC\Commons\Libraries\FileSystem;
use EWC\Config\Interfaces\IConfigParser;
use EWC\Config\Interfaces\IConfigWrapper;
use EWC\Config\Parsers\YAML;
use EWC\Config\Parsers\Conf;
use EWC\Config\Parsers\PHPArray;
use EWC\Config\Parsers\JSON;
use EWC\Config\Parsers\CLI;
use EWC\Config\Exceptions\ConfigException;
use EWC\Commons\Utilities\Reflector;
use Exception;

/**
 * Class Config Parser Factory
 * 
 * Act as a factory to parse config files into usable objects.
 *
 * @version 1.1.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 * 
 * @uses	FileSystem To verify the config source files exist.
 * @uses	IConfigParser To verify parser types.
 * @uses	IConfigWrapper To identify the parsed config as an interactive object.
 * @uses	YAML For a YAML config file parser support.
 * @uses	Conf For a BaSH style conf config file parser support.
 * @uses	PHPArray For a PHP Array config file parser support.
 * @uses	JSON For a JSON file config file parser support.
 * @uses	ConfigException Catches and throws named exceptions.
 * @uses	Reflector Dynamically use config parsers.
 * @uses	Exception To catch multiple named exceptions.
 */
class Parser {
		
	/**
	 * @var	String The YAML config parser type.
	 */
	const TYPE_YAML = "YAML";
		
	/**
	 * @var	String The BaSH style conf config parser type.
	 */
	const TYPE_CONF = "CONF";
		
	/**
	 * @var	String The PHP array config parser type.
	 */
	const TYPE_PHP_ARRAY = "PHP_ARRAY";
		
	/**
	 * @var	String The JSON file config parser type.
	 */
	const TYPE_JSON = "JSON";
		
	/**
	 * @var	String The Command Line Interface Argument config parser type.
	 */
	const TYPE_CLI = "CLI";
	
	/**
	 * @var	Parser The Config Parser object singleton instance reference.
	 */
	protected static $ourInstance = NULL;
	
	/**
	 * @var	Array Collection of config files loaded as IConfigWrapper objects.
	 */
	private static $loaded_configs = [];
	
	/**
	 * Parser constructor.
	 */
	protected function __construct() { }
	
	/**
	 * This object should be treated as a singleton instance.
	 * 
	 * @return	Parser A singleton instance.
	 */
	public static function getInstance() {
		if(is_null(static::$ourInstance)) {
		// initialise the object
			static::$ourInstance = new static();
		}
		return static::$ourInstance;
	}
	
	/**
	 * Load, parse and return the requested config.
	 * 
	 * @param	String $parser_type The config parser type to load the config.
	 * @param	String $config_file Optional full path to a config file to load and parse.
	 * @return	IConfigWrapper The loaded config as in interactively wrapped object.
	 * @throws	ConfigException If the specified config file does not exist.
	 * @throws	ConfigException If the config parser type is unknown.
	 * @throws	ConfigException If the config can not be loaded and parsed.
	 */
	public static function load($parser_type, $config_file=NULL) {
		// verify the source file exists and is readable
		if(!is_null($config_file) && !FileSystem::fileExists($config_file)) { throw ConfigException::withfailedToLoadConfigSource($config_file, $parser_type); }
		// hash the path to identify the config object
		$hash = md5($config_file);
		if(!array_key_exists($hash, static::$loaded_configs)) {
		// config object needs to be parsed & loaded
			static::cacheConfig($parser_type, $config_file, $hash);
		}
		// return the loaded config object
		return static::$loaded_configs[$hash];
	}
	
	/**
	 * Get the config parser for the config type.
	 * 
	 * @param	String $parser_type The config parser type to load the config.
	 * @return	IConfigParser The config parser.
	 * @throws	ConfigException If the config parser type is unknown.
	 */
	protected static function getParser($parser_type) {
		switch (strtoupper($parser_type)) {
		// determine which parser to use
			case static::TYPE_YAML:
				return YAML::class;
			case static::TYPE_CONF:
				return Conf::class;
			case static::TYPE_PHP_ARRAY:
				return PHPArray::class;
			case static::TYPE_JSON:
				return JSON::class;
			case static::TYPE_CLI:
				return CLI::class;
			default:
				// throw unknown config parser type exception
				throw ConfigException::withUnknownParserType($parser_type);
		}
		
	}
	
	/**
	 * Load, parse and cache the requested config.
	 * 
	 * @param	String $parser_type The config parser type to load the config.
	 * @param	String $config_file The full path to the config file to load and parse.
	 * @param	String $hash A hash to identify the config in the cache.
	 * @throws	ConfigException If the config parser type is unknown.
	 * @throws	ConfigException If the config can not be loaded and parsed.
	 */
	private static function cacheConfig($parser_type, $config_file, $hash) {
		$parser_classname = static::getParser($parser_type);
		try {
			// get a reflection of the parser
			$parser = new Reflector($parser_classname, [$config_file]);
			// verify the type so the config file can be
			$parser->verifyType(IConfigParser::class);
			// run the method to load and parse the config 
			$parsed_config = $parser->getReflectedSingleton("loadConfig");
			// @todo verify the type of the parsed config against IConfigWrapper
		} catch (Exception $ex) {
		// something went wrong parsing the source file
			throw ConfigException::withfailedToLoadConfigSource($config_file, $parser_type, $ex);
		}
		// add the loaded and parsed config data for future use
		static::$loaded_configs[$hash] = $parsed_config;
	}

}
