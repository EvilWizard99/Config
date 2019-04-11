<?php

namespace EWC\Config;

use EWC\Commons\Libraries\FileSystem;
use EWC\Config\Interfaces\IConfigParser;
use EWC\Config\Interfaces\IConfigWrapper;
use EWC\Config\Parsers\YAML;
use EWC\Config\Parsers\Conf;
use EWC\Config\Exceptions\ConfigException;
use EWC\Commons\Utilities\Reflector;
use Exception;

/**
 * Class Config Parser Factory
 * 
 * Act as a factory to parse config files into usable objects.
 *
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 * 
 * @uses	FileSystem To verify the config source files exist.
 * @uses	IConfigParser To verify parser types.
 * @uses	IConfigWrapper To identify the parsed config as an interactive object.
 * @uses	YAML For a YAML config parser support.
 * @uses	Conf For a BaSH style conf config parser support.
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
	 * @param	String $config_file The full path to the config file to load and parse.
	 * @param	String $parser_type The config parser type to load the config.
	 * @return	IConfigWrapper The loaded config as in interactively wrapped object.
	 * @throws	ConfigException If the config can not be loaded and parsed.
	 */
	public static function load($config_file, $parser_type) {
		// hash the path to identify the config object
		$hash = md5($config_file);
		if(!array_key_exists($hash, static::$loaded_configs)) {
		// config object needs to be parsed & loaded
			static::cacheConfig($config_file, $parser_type, $hash);
		}
		// return the loaded config object
		return static::$loaded_configs[$hash];
	}
	
	/**
	 * Load, parse and cache the requested config.
	 * 
	 * @param	String $config_file The full path to the config file to load and parse.
	 * @param	String $parser_type The config parser type to load the config.
	 * @param	String $hash A hash to identify the config in the cache.
	 * @throws	ConfigException If the config can not be loaded and parsed.
	 */
	private static function cacheConfig($config_file, $parser_type, $hash) {
		// verify the source file exists and is readable
		if(!FileSystem::fileExists($config_file)) { throw ConfigException::withfailedToLoadConfigSource($config_file); }
		switch (strtoupper($parser_type)) {
		// determine which parser to use
			case static::TYPE_YAML:
				$parser_classname = YAML::class;
			break;
			case static::TYPE_CONF:
				$parser_classname = Conf::class;
			break;
			default:
				// throw unknown config parser type exception
				throw ConfigException::withUnknownParserType($parser_type);
		}
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
