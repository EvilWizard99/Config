<?php

namespace EWC\Config\Interfaces;

/**
 * Interface IConfigParser
 * 
 * Define the method signatures for parsing config sources.
 *
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
interface IConfigParser {
	
	/**
	 * Get the config parser type.
	 * 
	 * @return	String The config source parser type.
	 */
	public static function getParserType();
	
	/**
	 * Load the specified config source.
	 * 
	 * @param	String $config_file The config filename to load and parse.
	 * @return	MetaData The loaded config object as metadata.
	 */
	public static function loadConfig($config_file);

}
