<?php

namespace EWC\Config\Exceptions;

use Exception;

/**
 * Exception ConfigParserException
 * 
 * Group ConfigParser exceptions and errors.
 *
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 * 
 * @uses	ConfigException As an exception base.
 * @uses	Exception To rethrow.
 */
class ConfigParserException extends ConfigException {
	
	/**
	 * ConfigParserException constructor.
	 * 
	 * @param	String $message An error message for the exception.
	 * @param	Integer $code An error code for the exception.
	 * @param	Exception $previous An optional previously thrown exception.
	 */
    public function __construct($message, $code=ConfigParserException::CODE_GENERAL, Exception $previous=NULL)  {
        parent::__construct($message, $code, $previous);
    }
}
