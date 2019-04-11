<?php

namespace EWC\Config\Exceptions;

use RuntimeException;
use Exception;

/**
 * Exception ConfigException
 * 
 * Group Config exceptions and errors.
 *
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 * 
 * @uses	RuntimeException As an exception base.
 * @uses	Exception To rethrow.
 */
class ConfigException extends RuntimeException {
	
	/**
	 * @var	Integer Code for general or unknown issues.
	 */
	const GENERAL = 0;
	
	/**
	 * @var	Integer Code for config section name already loded and populated.
	 */
	const CONFIG_SECTION_ALREADY_LOADED = 2;
	
	/**
	 * @var	Integer Code for invalid config source.
	 */
	const INVALID_CONFIG_SOURCE = 10;
	
	/**
	 * @var	Integer Code foe unknown parsed config structure.
	 */
	const UNKNOWN_CONFIG_SOURCE_STRUCTURE = 11;
	
	/**
	 * @var	Integer Code failing to load config from source.
	 */
	const FAILED_TO_LOAD_CONFIG_SOURCE = 20;
	
	/**
	 * @var	Integer Code foe attempting to use an unknown parser type.
	 */
	const UNKNOWN_PARSER_TYPE = 30;
	
	/**
	 * @var	Integer Code failing to parse config source.
	 */
	const FAILED_TO_PARSE_CONFIG_SOURCE = 31;
	
	/**
	 * ConfigException constructor.
	 * 
	 * @param	String $message An error message for the exception.
	 * @param	Integer $code An error code for the exception.
	 * @param	Exception $previous An optional previously thrown exception.
	 */
    public function __construct($message, $code=ConfigException::CODE_GENERAL, Exception $previous=NULL)  {
        parent::__construct($message, $code, $previous);
    }
	
	/**
	 * Generate a config section already loaded exception.
	 * 
	 * @return	ConfigException
	 */
	public static function withSectionAlreadyLoaded() {
		return new static("Config setion already loaded", static::CONFIG_SECTION_ALREADY_LOADED);
	}
	
	/**
	 * Generate a failed to load config source exception.
	 * 
	 * @param	String $source_file The path to the config file attempted to be loaded.
	 * @param	String $parser_type The config parser type attempted to be used.
	 * @param	Exception $previous The caught parsing exception exception.
	 * @return	ConfigException
	 */
	public static function withfailedToLoadConfigSource($source_file, $parser_type, Exception $previous=NULL) {
		return new static("Failed to load config source file [{$source_file}] using [{$parser_type}] parser.", static::INVALID_CONFIG_SOURCE, $previous);
	}
	
	/**
	 * Generate a failed to parse config source exception.
	 * 
	 * @param	String $source_file The path to the config file attempted to be parsed.
	 * @param	String $parser_type The config parser type attempted to be used.
	 * @param	Exception $previous The caught parsing exception exception.
	 * @return	ConfigException
	 */
	public static function withfailedToParseConfigSource($source_file, $parser_type, Exception $previous) {
		return new static("Failed to parse config source file [{$source_file}] using [{$parser_type}] parser.", static::INVALID_CONFIG_SOURCE, $previous);
	}
	
	/**
	 * Generate an unknown parser exception.
	 * 
	 * @param	String $type The parser type attempted to be used.
	 * @return	ConfigException
	 */
	public static function withUnknownParserType($type) {
		return new static("Unknown parser type [{$type}].", static::UNKNOWN_PARSER_TYPE);
	}
	
}
