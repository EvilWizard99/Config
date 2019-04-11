<?php

namespace EWC\Config;

use EWC\Commons\Traits\TMetadata;
use EWC\Config\Exceptions\ConfigException;

/**
 * Class Config
 * 
 * Interface between application and loaded config values from various source types.
 *
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2018 Evil Wizard Creation.
 * 
 * @uses	TMetadata The metadata Traits functionality.
 * @uses	Parser To parse the configuration files.
 * @uses	ConfigException Catches and throws named exceptions.
 * @uses	MetadataTraitException Catches and throws named exceptions.
 */
class Config {
	
	/**
	 * @var	Config The Config object singleton instance reference.
	 */
	protected static $ourInstance = NULL;
	
	/**
	 * @var	String The base path to load and parse config source from.
	 */
	protected $config_base_path;
	
	/**
	 * Includes the following TMetadata trait methods for use.
	 * 
	 * The trait provides no public access methods.
	 * 
	 * Adds the following methods for protected access.
	 * 
	 * traitMetadataSet($source)
	 * traitMetadataUnset($path)
	 * traitMetadataHas($path)
	 * traitMetadataGetValue($path)
	 * traitMetadataGetAsArray()
	 * traitMetadataAddValue($name, $data, $path, $update)
	 * traitMetadataSetPathSeparator($new_path_separator)
	 * traitMetadataGetPathSeparator()
	 * traitMetadataDebug()
	 * traitMetadataGetAsJsonString($json_options)
	 */
	use TMetadata;
	
	/**
	 * Config constructor.
	 */
	protected function __construct() {
		// set the base path to load config files from
		$this->config_base_path = APP_ROOT . "/config/";
		// define the config metadata base
		$config_metadata = [
			"__loaded"	=> [],
			"public"	=> [],
			"protected"	=> [],
			"private"	=> []
		];
		// define the config metadata structure
		$this->traitMetadataAddValue("configs", $config_metadata, NULL, TRUE);
	}
	
	/**
	 * This object should be treated as a singleton instance.
	 * 
	 * @return	Config A singleton instance.
	 */
	public static function getInstance() {
		if(is_null(static::$ourInstance)) {
		// initialise the object
			static::$ourInstance = new static();
		}
		return static::$ourInstance;
	}
		
	/**
	 * Facade access method to check if the config has already been loaded.
	 * 
	 * @return	Boolean TRUE id the specified config has been loaded.
	 */
	public static function isLoaded($source_file_hash, $scope=NULL) { return static::getInstance()->isConfigLoaded($source_file_hash, $scope); }
		
	/**
	 * Facade access method to loadConfig($config_uri, $type, $name=NULL, $scope=NULL).
	 * 
	 * @param	String $config_uri URI to the config file.
	 * @param	String $type The config source file parser type.
	 * @param	String $name Optional config section key to load the config into.
	 * @param	Trinary $scope Trinary flag to indicate the config access scope.
	 * @throws	ConfigException If the config has already been loaded or unable to be loadded.
	 */
	public static function load($config_uri, $type, $name=NULL, $scope=NULL) { return static::getInstance()->configLoaded($config_uri, $type, $name, $scope); }
	
	/**
	 * Check if the config has already been loaded.
	 * 
	 * @return	Boolean TRUE id the specified config has been loaded.
	 */
	public function isConfigLoaded($source, $scope=NULL) { 
		$section_scope = $this->getConfigScope($scope);
		return ($this->traitMetadataHas("configs.{$section_scope}.{$source}") || $this->traitMetadataHas("configs.__loaded.{$section_scope}.{$source}"));
	}
	
	/**
	 * Load a config source for use.
	 * 
	 * @param	String $config URI to the config file.
	 * @param	String $type The config source file parser type.
	 * @param	String $name Optional config section key to load the config into.
	 * @param	Trinary $scope Trinary flag to indicate the config access scope.
	 * @throws	ConfigException If the config has already been loaded or unable to be loadded.
	 */
	public function loadConfig($config, $type, $name=NULL, $scope=NULL) {
		$config_source = "{$this->config_base_path}{$config}";
		$config_hash = md5("{$config_source}-{$type}");
		if($this->isConfigLoaded($config_hash, $scope)) {
		// the config source has already been loaded
			// @todo log the double source loading
			// @toda throw exception if $scope != NULL
			// @todo see if the name of the loaded source hash config is the same as 
			//       the loading one and set the loading config to the loaded one,
			//       log double named source loading
		} else {
		// the config source needs loading, parsing and adding as metadata for access
			if(!is_null($name) && $this->isConfigLoaded($name, $scope)) {
			// the config section name has already been populated
				// @throw section already loaded exception
			}
			// get the config parser
			try {
				$parser = $this->getConfigParser($type);
			} catch (Exception $ex) {
				// @todo log the failure to load the config source
			}
			$config = $parser->parseSource($config_source);
			$scoped_section = $this->getConfigScope($scope);
			// @todo add the parsed config metadata to the configs
			// define the config metadata structure
			if(is_null($name)) {
			// the config goes in the common section
			} else {
			// config goes in the named section
				$this->traitAddMetadataValue($name, $config, "configs.{$scoped_section}", FALSE);
			}
			// @todo add the parser metadata for parsed config
			
		}
		if(!array_key_exists($config_hash, $this->loaded_configs)) {
		// config object needs to be parsed & loaded
			
			// @todo add the parser details to the metadata???
			$this->loaded_configs[$name] = ConfigParserYAML::loadConfig($config_source);
		} else {
			// @todo throw exception about already loaded
		}
		// return the loaded YAML config object
		return $this->loaded_configs[$name];
	}
	
	/**
	 * Get a previously loaded YAML config file object.
	 * 
	 * @param	String $config Full path to the YAML config file
	 * @return	AConfigParser The loaded YAML config object.
	 */
	public function getConfig($config) {
		// hash the path to identify the config object
		$hash = md5($config);
		if(!array_key_exists($hash, static::$loaded_configs)) {
		// config object is not loaded
			// @todo throw exception
		}
		// return the loaded YAML config object
		return static::$loaded_configs[$hash];
		
	}
	
	protected function getConfigParser($type) {
		switch($type) {
			case static::SOURCE_TYPE_YAML :
				$parser = ConfigParserYAML::getParser();
			break;
			default :
				// @todo throw invalid parser type exception
		}
		// @todo setup the parser
		return $parser;
	}
	
	/**
	 * Get the config section scope name.
	 * 
	 * @param	Trinary $scope Flag to indicate the config scope, NULL = common, FALSE = protected, TRUE = private
	 * @return	String The config scope section name.
	 */
	private function getConfigScope($scope=NULL) {
		return ((is_null($scope))
				? "public"
				: (($scope)
					? "private" 
					: "protected"
				)
		);
	}
	
	private function addParserMetdata(AConfigParser $parser) {
		// configs.__loaded
		
	}
}
