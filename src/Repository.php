<?php
/**
* @author 		Peter Taiwo <peter@phoxphp.com>
* @package 		Kit\PhoxEngine\Repository
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

use Kit\PhoxEngine\Config;
use Kit\PhoxEngine\Contracts\RepositoryContract;

class Repository implements RepositoryContract
{

	/**
	* @var 		$options
	* @access 	protected
	*/
	protected 	$options = [];

	/**
	* @var 		$view
	* @access 	protected
	*/
	protected 	$view;

	/**
	* @var 		$path
	* @access 	protected
	*/
	protected 	$path;

	/**
	* @var 		$extension
	* @access 	protected
	*/
	protected 	$extension;

	/**
	* @var 		$config
	* @access 	protected
	*/
	protected 	$config;

	/**
	* @var 		$variables
	* @access 	protected
	*/
	protected 	$variables = [];

	/**
	* @var 		$hiddenVariables
	* @access 	protected
	*/
	protected 	$hiddenVariables = [];

	/**
	* {@inheritDoc}
	*/
	public function __construct(Array $options=[])
	{
		$this->options = $options;
		$this->config = new Config();

		$this->config->bindAll($this);
	}

	/**
	* {@inheritDoc}
	*/
	public function setView(String $view)
	{
		$this->options['view'] = $view;
	}

	/**
	* {@inheritDoc}
	*/
	public function setPath(String $path)
	{
		$this->options['path'] = $path;
	}

	/**
	* {@inheritDoc}
	*/
	public function setExtension(String $extension)
	{
		$this->options['extension'] = $extension;
	}

	/**
	* {@inheritDoc}
	*/	
	public function setVariable(String $variableName, $value=null) : RepositoryContract
	{
		$this->variables[$variableName] = $value;
		return $this;
	}

	/**
	* {@inheritDoc}
	*/
	public function getView()
	{
		return $this->options['view'] ?? null;
	}

	/**
	* {@inheritDoc}
	*/
	public function getPath()
	{
		return $this->options['path'] ?? null;
	}

	/**
	* {@inheritDoc}
	*/
	public function getExtension()
	{
		return $this->options['extension'] ?? null;
	}

	/**
	* {@inheritDoc}
	*/
	public function getVariable(String $variableName)
	{
		return $this->variables[$variableName] ?? false;
	}

	/**
	* {@inheritDoc}
	*/
	public function getVariables() : Array
	{
		return $this->variables;
	}

	/**
	* Renders a view.
	*
	* @param 	$view <String>
	* @access 	public
	* @return 	<void>
	*/
	public function render(String $view=null)
	{
		// If $view parameter is not null, we will set this as the view to be rendered.
		if ($view !== null) {
			$this->setView($view);
		}

		$renderer = new Renderer($this);
		return $renderer->render();
	}

	/**
	* Sets a hidden variable.
	*
	* @param 	$variableName <String>
	* @param 	$value <Mixed>
	* @access 	public
	* @return 	<void>
	*/
	public function setHiddenVariable(String $variableName, $value=null)
	{
		$this->hiddenVariables[$variableName] = $value;
	}

	/**
	* Returns a hidden variable.
	*
	* @param 	$variableName <String>
	* @access 	public
	* @return 	<Mixed>
	*/
	public function getHiddenVariable(String $variableName)
	{
		return $this->hiddenVariables[$variableName] ?? false;
	}

	/**
	* Returns an array of hidden variables.
	*
	* @access 	public
	* @return 	<Array>
	*/
	public function getHiddenVariables()
	{
		return $this->hiddenVariables;
	}

	/**
	* Returns path to a view.
	*
	* @param 	$view <String>
	* @access 	public
	* @return 	<String>
	*/
	public function getViewPath() : String
	{
		return $this->getPath() . '/' . $this->getView();
	}

	/**
	* Returns path to a view with it's extension.
	*
	* @access 	public
	* @return 	<String>
	*/
	public function getViewWithExtension() : String
	{
		return $this->getPath() . '/' . $this->getView() . '.' . $this->getExtension();
	}

	/**
	* Returns an option value from the options array.
	*
	* @param 	$option <String>
	* @access 	public
	* @return 	<Mixed>
	*/
	public function getOpt(String $option=null)
	{
		return $this->options[$option] ?? null;
	}

	/**
	* @access 	public
	* @return 	<Object> <Kit\PhoxEngine\Repository>
	* @static
	*/
	public static function getInstance()
	{
		return new self();	
	}

}