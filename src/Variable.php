<?php
/**
* @author 		Peter Taiwo <peter@phoxphp.com>
* @package 		Kit\PhoxEngine\Variable
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

use Kit\PhoxEngine\Contracts\RepositoryContract;
use Kit\PhoxEngine\Exceptions\VariableNotFoundException;

class Variable
{

	/**
	* @var 		$repository
	* @access 	protected
	*/
	protected 	$repository;

	/**
	* @var 		$variable
	* @access 	protected
	*/
	protected 	$variable = null;

	/**
	* Variable constructor
	*
	* @param 	$repository <Kit\PhoxEngine\Contracts\RepositoryContract>
	* @param 	$variable <String>
	* @access 	public
	* @return 	<void>
	*/
	public function __construct(RepositoryContract $repository, String $variable=null)
	{
		$this->repository = $repository;
		$this->variable = $variable;
	}

	/**
	* Returns parsed variable.
	*
	* @access 	public
	* @return 	<String>
	*/
	public function getParsedVariable()
	{
		$variableType = $this->getVariableType($this->variable);

		if ($variableType == 1 && !$this->repository->getVariable(str_replace("\$", '', $this->variable))) {
			throw new VariableNotFoundException(sprintf('Variable %s does not exist.', $this->variable));
		}

		return $this->variable;
	}

	/**
	* Returns a variable type.
	*
	* @param 	$var <String>
	* @access 	public
	* @return 	<Integer>
	*/
	public function getVariableType(String $var)
	{
		// Checking if start of string is dollar sign..
		if ($var[0] == "\$") {
			return 1;
		}

		// Checking if variable is an array type.
		if ($var[0] == "[" && $var[strlen($var) - 1] == "]") {
			return 2;
		}
	}

}