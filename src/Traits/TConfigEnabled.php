<?php

namespace EWC\Config\Traits;

use EWC\Commons\Traits\TErrors;
use EWC\Config\Parser;
use EWC\Config\Interfaces\IConfigWrapper;
use EWC\Config\Exceptions\ConfigException;

/**
 * Trait TConfigEnabled
 * 
 * Add the use of YAML config setting file support.
 * 
 * Provides no public access methods.
 * 
 * Provides the following inherited public access methods from TErrors trait.
 * 
 * getLastError()
 * getErrors()
 * 
 * Provides the following protected methods for access.
 * 
 * traitLoadConfig($config_source)
 * traitGetConfigValue($path, $default=FALSE)
 * 
 * Provides the following inherited protected access methods from TErrors trait.
 * 
 * addError($error, $trigger=FALSE)
 * logException(Exception $ex)
 * 
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 * 
 * @uses	Parser To parse the configuration source file.
 * @uses	IConfigWrapper The config values.
 * @uses	ConfigException Catches and throws named exceptions.
 */
trait TConfigEnabled {
	
	/**
	 * Include the TErrors traits.
	 * 
	 * Provides the following methods for public access.
	 * 
	 * getLastError()
	 * getErrors()
	 * 
	 * Provides the following methods for protected access.
	 * 
	 * addError($error, $trigger=FALSE)
	 * logException(Exception $ex)
	 */
	use TErrors;
	
	/*
	 * @var	IConfigWrapper The loaded config settings.
	 */
	private $config;
	
	/*
	 * @var	String The root node name of the loaded config structure.
	 */
	private $config_root;
	
	/**
	 * Load and parse a config file for use.
	 * 
	 * @param	String $config_source The absolute path to the config file to load.
	 * @param	String $config_root The config root / group of the config.
	 * @param	String $parser_type The config parser type used to load the config.
	 * @throws	ConfigException If unable to load or parse the config file.
	 */
	protected function traitLoadConfig($config_source, $config_root, $parser_type) {
		try {
			// get the config from the yaml source
			$this->config = Parser::load($config_source, $parser_type);
		} catch (ConfigException $ex) {
		// unable to load the config, log and rethrow
			$this->logException($ex);
			ConfigException::withFailedToLoadConfigSource($config_source, $parser_type, $ex);
		}
		// set the root node name of the config source structure
		$this->config_root = $config_root;
	}
	
	/**
	 * Get a value from the config.
	 * 
	 * @param	String $path The config structure path.
	 * @param	Mixed $default The config value to use if the path does not exist.
	 * @return	String The value from the config.
	 */
	protected function traitGetConfigValue($path, $default=FALSE) { return $this->config->get("{$this->config_root}.{$path}", $default); }
	
}
