<?php

namespace EWC\Config\Interfaces;

use EWC\Config\Exceptions\ConfigException;

/**
 * Interface IConfigWrapper
 * 
 * @version 1.1.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 * 
 * @uses	ConfigException Catches and throws named exceptions.
 */
interface IConfigWrapper extends IConfigExposure {
	
	/**
	 * Create an interactive config object from a JSON encoded source string.
	 * 
	 * @param	Mixed $source Either a JSON encoded source string or n source Array to convert.
	 * @param	String $config_file The full path to the config file used to load the config.
	 * @param	String $parser_type The config parser type used to load the config.
	 * @return	IConfigWrapper The source as interactive metadata.
	 * @throws	ConfigException If the metadata has already been set, the source is not an array or a valid JSON String.
	 */
	public static function create($source, $config_file, $parser_type);
	
	/**
	 * Import another config.
	 * 
	 * @param	IConfigWrapper $config An already loaded and parsed config.
	 * @return	ConfigWrapper The source as interactive metadata.
	 * @throws	ConfigException If the source is not an array or a valid JSON String.
	 */
	public function importConfig(IConfigWrapper $config);	
	
	/**
	 * Check if the config has the specified setting path.
	 * 
	 * @param	String $path The config setting structure path.
	 * @return	Boolean TRUE if the path exists within the structure.
	 */
	public function has($path);	
	
	/**
	 * Get a config value from a loaded config.
	 * 
	 * @param	String $path The config section path to get the value of.
	 * @param	Mixed $default An optional default value to return if the section key is not found in the config.
	 * @param	Boolean $keys Flag to indicate the section keys should be returned not the section contents.
	 * @return	Mixed The specified config.
	 */
	public function get($path, $default=FALSE, $keys=FALSE);	
	
	/**
	 * Get a numeric config value of the path of the config structure.
	 * 
	 * @param	String $name The config section name.
	 * @param	String $path Optional config structure path to add the key to.
	 * @return	Number The numeric config value from the specified path.
	 * @throws	ConfigException If the config section key is not numeric.
	 */
	public function getNumeric($name, $path=NULL);
	
	/**
	 * Get a list of config keys.
	 * 
	 * @return	Array List of keys in the loaded config.
	 */
	public function getKeys();	
	
	/**
	 * Get a wrapped sub section of the config.
	 * 
	 * @param	String $path The config structure path.
	 * @return	IConfigWrapper The source as interactive metadata.
	 * @throws	ConfigException If the sub setcion is not an array or a valid JSON String.
	 */
	public function getSubSection($path);
	
	/**
	 * Get the config as an associative array.
	 * 
	 * @return	Array The config as an associative array.
	 */
	public function toArray();
	
	/**
	 * @return	String The loaded config array structure.
	 */
	public function __toString();
	
}
