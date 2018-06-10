<?php
/**
* @author 		Peter Taiwo <peter@phoxphp.com>
* @package 		Kit\PhoxEngine\Config
* @license 		MIT License
*
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
* SOFTWARE.
*/

namespace Kit\PhoxEngine;

use ReflectionClass;
use RuntimeException;
use Kit\PhoxEngine\Repository;

class Config
{

	/**
	* Returns coniguration key.
	*
	* @param 	$key <String>
	* @access 	public
	* @return 	<Mixed>
	* @throws 	RuntimeException
	*/
	public function get(String $key=null)
	{
		$configPath = dirname(__DIR__) . '/src/public/config/system.php';
		if (!file_exists($configPath)) {
			throw new RuntimeException(sprintf('Unable to load configuration file in {%s}', $configPath));
		}

		$config = include $configPath;
		return $config[$key] ?? $config;
	}

	/**
	* Binds all configuration to repository.
	*
	* @param 	$store <Kit\PhoxEngine\Repository>
	* @access 	public
	* @return 	<void>
	*/
	public function bindAll(Repository $store)
	{
		$storeReflection = new ReflectionClass($store);
		$optionProperty = $storeReflection->getProperty('options');

		// Make this property accessible to modify it's values.
		$optionProperty->setAccessible(true);
		$keys = $this->get();
		$options = [];
		foreach(array_keys($keys) as $key) {
			$options[$key] = $keys[$key];
		}

		$optionProperty->setValue($store, $options);
		$optionProperty->setAccessible(false);
	}

	/**
	* @access 	public
	* @return 	<Object> <Kit\PhoxEngine\Config>
	* @static
	*/
	public static function getInstance()
	{
		return new self();
	}

}