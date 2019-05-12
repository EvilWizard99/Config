<?php

namespace EWC\Config\Tests\Traits;

use PHPUnit_Framework_TestCase;

/**
 * Corresponding Test Class for \EWC\Config\Traits\TConfigEnabled
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
class TConfigEnabledTest extends PHPUnit_Framework_TestCase {
	
	protected $trait_name = "\EWC\Config\Traits\TConfigEnabled";
	
	/**
	 * Just check if the TConfigEnabled has no syntax error.
	 */
	public function testIsThereAnySyntaxError() {
		$trait = $this->getMockForTrait($this->trait_name);
		$this->assertTrue(is_object($trait));
		unset($trait);
	}
	
	/**
	 * Make sure the TConfigEnabled returns the last error as expected when there are no errors in the stack.
	 */
	public function testGetLastErrorReturnsEmptyWhenNoErrorsAdded() {
		$trait = $this->getMockForTrait($this->trait_name);
		$last_error = $trait->getLastError();
		$this->assertEmpty($last_error, "Trait last error is not empty with no errors set");
		$this->assertEquals('', $last_error, "Trait last error is not empty with no errors set");
		unset($trait);
	}
	
	/**
	 * Make sure the TConfigEnabled returns all errors as expected when there are no errors in the stack.
	 */
	public function testGetErrorReturnsEmptyArrayWhenNoErrorsAdded() {
		$trait = $this->getMockForTrait($this->trait_name);
		$all_errors = $trait->getErrors();
		$this->assertEmpty($all_errors, "Trait errors is not empty to start");
		$this->assertCount(0, $all_errors, "Trait errors is not empty to start");
		unset($trait);
	}
	
}
