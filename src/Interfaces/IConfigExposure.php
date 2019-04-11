<?php

namespace EWC\Config\Interfaces;

/**
 * Interface IConfigExposure
 * 
 * Define the method signatures for allowing access to config setting values.
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
interface IConfigExposure {
	
	/**
	 * Expose a value from the config.
	 * 
	 * @param	String $path The config structure path.
	 * @param	Mixed $default The config value to use if the path does not exist.
	 * @return	String The exposed value from the config.
	 */
	public function getConfigSetting($path, $default);
	
}
