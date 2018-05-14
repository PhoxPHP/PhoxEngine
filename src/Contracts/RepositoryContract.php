<?php
/**
* MIT License
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:

* The above copyright notice and this permission notice shall be included in all
* copies or substantial portions of the Software.

* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
* SOFTWARE.
*/

/**
* @author 	Peter Taiwo
* @package 	Kit\PhoxEngine\Contracts\RepositoryContract
*/

namespace Kit\PhoxEngine\Contracts;

interface RepositoryContract
{

	/**
	* Constructor accepts an argument of array type. The array should contain keys and values
	* which will be used as configurations.
	*
	* The only required configuration keys are the 'view' key which value must be the name of the
	* template that will be rendered, the 'path' key which is the path where templates are placed
	* and also extension which is the template extension.
	*
	* @param 	$options <Array>
	* @access 	public
	* @return 	void
	*/
	public function __construct(Array $options=[]);

	/**
	* This method can be used to set the view explicitly. It accepts an argument of string type
	* which is the name of the view template to be rendered.
	*
	* @param 	$view <String>
	* @access 	public
	* @return 	void
	*/
	public function setView(String $view);

	/**
	* This method can be used to set the path where templates are placed and where they will be loaded.
	*
	* @param 	$path <String>
	* @access 	public
	* @return 	void
	*/
	public function setPath(String $path);

	/**
	* This method can be used to set the extension of the templates that will be rendered.
	*
	* @param 	$extension <String>
	* @access 	public
	* @return 	void
	*/
	public function setExtension(String $extension);

	/**
	* Sets a view variable.
	*
	* @param 	$variableName <String>
	* @param 	$value <Mixed>
	* @access 	public
	* @return 	Object <Kit\PhoxEngine\Contracts\RepositoryContract>
	*/
	public function setVariable(String $variableName, $value=null) : RepositoryContract;

	/**
	* Returns rendered view.
	*
	* @access 	public
	* @return 	String
	*/
	public function getView();

	/**
	* Returns template path.
	*
	* @access 	public
	* @return 	String
	*/
	public function getPath();

	/**
	* Returns template extension.
	*
	* @access 	public
	* @return 	String
	*/
	public function getExtension();

	/**
	* Returns a view variable.
	*
	* @param 	$variableName <String>
	* @access 	public
	* @return 	Mixed
	*/
	public function getVariable(String $variableName);

	/**
	* Returns variables.
	*
	* @access 	public
	* @return 	Array
	*/
	public function getVariables() : Array;

}