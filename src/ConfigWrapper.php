<?php

namespace EWC\Config;

use EWC\Config\Interfaces\IConfigWrapper;
use EWC\Commons\Utilities\MetaData;
use EWC\Commons\Exceptions\MetadataTraitException;
use EWC\Config\Exceptions\ConfigException;

/**
 * Class ConfigWrapper
 * 
 * @version 1.1.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 * 
 * @uses	IConfigParser To define method / object signatures.
 * @uses	MetaData The JSON metadata functionality.
 * @uses	MetadataTraitException Catches and throws named exceptions.
 * @uses	ArrayPathTraitException Catches and throws named exceptions.
 * @uses	ConfigException Catches and throws named exceptions.
 */
class ConfigWrapper implements IConfigWrapper {
	
	/**
	 * @var	String The full path to the config file used to load the settings.
	 */
	protected $source_file;
	
	/**
	 * @var	String The config parser type used.
	 */
	protected $parser_type;
	
	/**
	 * @var	MetaData The config as metadata.
	 */
	protected $config;
	
	/**
	 * ConfigWrapper constructor.
	 * 
	 * @param	Mixed $source Either a JSON encoded source string or n source Array to convert.
	 * @param	String $config_file The full path to the config file used to load the config.
	 * @param	String $parser_type The config parser type used to load the config.
	 * @throws	ConfigException If the source is not an array or a valid JSON String.
	 */
	protected function __construct($source, $config_file, $parser_type) {
		// set the parser details & source file
		$this->source_file = $config_file;
		$this->parser_type = $parser_type;
		$this->setConfigFromSource($source);
	}

	/**
	 * Create an interactive config object from a JSON encoded source string.
	 * 
	 * @param	Mixed $source Either a JSON encoded source string or n source Array to convert.
	 * @param	String $config_file The full path to the config file used to load the config.
	 * @param	String $parser_type The config parser type used to load the config.
	 * @return	ConfigWrapper The source as interactive metadata.
	 * @throws	ConfigException If the source is not an array or a valid JSON String.
	 */
	public static function create($source, $config_file, $parser_type) { return new static($source, $config_file, $parser_type); }
	
	/**
	 * Import another config.
	 * 
	 * @param	IConfigWrapper $config An already loaded and parsed config.
	 * @return	ConfigWrapper The source as interactive metadata.
	 * @throws	ConfigException If the source is not an array or a valid JSON String.
	 */
	public function importConfig(IConfigWrapper $config) {
		// allow for overriding the current config values with imported ones
		$merged_config = array_merge_recursive($this->toArray(), $config->toArray());
		// set the new config data
		$this->setConfigFromSource($merged_config);
		return $this;
	}
	
	/**
	 * Check if the config has the specified setting path.
	 * 
	 * @param	String $path The config setting structure path.
	 * @return	Boolean TRUE if the path exists within the structure.
	 */
	public function has($path) { return $this->config->has($path); }
	
	/**
	 * Get a config value from a loaded config.
	 * 
	 * @param	String $path The config section path to get the value of.
	 * @param	Mixed $default An optional default value to return if the section key is not found in the config.
	 * @param	Boolean $keys Flag to indicate the section keys should be returned not the section contents.
	 * @return	Mixed The specified config.
	 */
	public function get($path, $default=FALSE, $keys=FALSE) {
		$config_setting = $this->config->get($path, $default);
		// return the config setting valus or the settings keys
		return ($keys && is_array($config_setting))
			? array_keys($config_setting)
			: $config_setting;
	}
	
	/**
	 * Get a numeric config value of the path of the config structure.
	 * 
	 * @param	String $name The config section name.
	 * @param	String $path Optional config structure path to add the key to.
	 * @return	Number The numeric config value from the specified path.
	 * @throws	ConfigException If the config section key is not numeric.
	 */
	public function getNumeric($name, $path=NULL) {
		// work out the metadata full path
		$full_path = (is_null($path)) ? $name : "{$path}{$this->config->getPathSeparator()}{$name}";
		// get the metadata value, or use 0
		$current_value = $this->get($full_path, 0);
		if(!is_numeric($current_value)) {
		// metadata value is not numeric
			throw new ConfigException("Config value is not numeric", MetadataTraitException::TYPE_MISMATCH);
		}
		// return the current metadata numeric value
		return $current_value;
	}
	
	/**
	 * Expose a value from the config.
	 * 
	 * @param	String $path The config structure path.
	 * @param	Mixed $default The config value to use if the path does not exist.
	 * @return	String The exposed value from the config.
	 */
	public function getConfigSetting($path, $default) { return $this->get($path, $default); }
	
	/**
	 * Get a list of config keys.
	 * 
	 * @return	Array List of keys in the loaded config.
	 */
	public function getKeys() { return array_keys($this->config->toArray()); }
	
	/**
	 * Get a wrapped sub section of the config.
	 * 
	 * @param	String $path The config structure path.
	 * @return	ConfigWrapper The source as interactive metadata.
	 * @throws	ConfigException If the sub setcion is not an array or a valid JSON String.
	 */
	public function getSubSection($path) {  return static::create($this->get($path), $this->source_file, $this->parser_type); }
		
	/**
	 * Get the config as an associative array.
	 * 
	 * @return	Array The config as an associative array.
	 */
	public function toArray() { return $this->config->toArray(); }
	
	/**
	 * @return	String The loaded config array structure.
	 */
	public function __toString() { return print_r($this->config->toArray(), TRUE); }
	
	/**
	 * Set the config source to metadata.
	 * 
	 * @param	Mixed $source Either a JSON encoded source string or n source Array to convert.
	 * @throws	ConfigException If the source is not an array or a valid JSON String.
	 */
	private function setConfigFromSource($source) {
		try {
			// create the config metadata from the source
			$this->config = MetaData::create($source);
		} catch (MetadataTraitException $ex) {
		// something went wrong with the config source structure
			throw new ConfigException("Invalid config structure parsed from [{$this->source_file}] using [{$this->parser_type}]", ConfigException::UNKNOWN_CONFIG_SOURCE_STRUCTURE, $ex);
		}		
	}
	
}
