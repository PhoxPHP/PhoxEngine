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
* @package 	Kit\PhoxEngine\Directives\Getter
*/

namespace Kit\PhoxEngine\Directives;

use RuntimeException;
use Kit\PhoxEngine\Attr;
use Kit\PhoxEngine\Variable;
use Kit\PhoxEngine\{Renderer, Repository};
use Kit\PhoxEngine\Directives\Helpers\ExtendHelper;
use Kit\PhoxEngine\Directives\Contract\DirectiveContract;

/*
* Usage:
* #{name} - Is equivalent to php's echo
* #{name : true} - Is equivalent to php's var_dump
*/

class Getter implements DirectiveContract
{

	/**
	* @var 		$engine
	* @access 	protected
	*/
	protected 	$engine;

	/**
	* @var 		$repository
	* @access 	protected
	*/
	protected 	$repository;

	/**
	* {@inheritDoc}
	*/
	public function __construct(Renderer $engine, Repository $repository)
	{
		$this->engine = $engine;
		$this->repository = $repository;
	}

	/**
	* {@inheritDoc}
	*/
	public function getCompiledMixin(String $parsed=null)
	{
		$data = null;

		$view = $this->repository->getViewWithExtension();
		$content = file_get_contents($view, true);
		$variable = new Variable($this->repository, null);

		if (preg_match_all(Attr::GETTER_ECHO_REGEX, $content, $matches)) {
			for($i = 0; $i < count($matches[0]); $i++) {

				$dir = $matches[0][$i];
				$name = $matches[1][$i];
				$argsArray = explode(':', $name);

				if (count($argsArray) < 1 || $name == null) {
					throw new RuntimeException('get directive expects at least one argument.');
				}

				if ($variable->getVariableType($name) == 1) {
					// throw new RuntimeException('You may not initialize a variable using $ sign.');
				}

				if (count($argsArray) > 2) {
					throw new RuntimeException('Maximum number of arguments exceeded in get directive.');
				}

				if (!isset($argsArray[1])) {
					// If variable exists, add dollar sign to variable.
					$data[$dir] = ($this->repository->getHiddenVariable($name))
					? '<?php echo $' . $name . '; ?>'
					: '<?php echo ' . $name . '; ?>';
				}else{
					if (trim($argsArray[1]) == 'true') {
						$data[$dir] = ($this->repository->getHiddenVariable($name))
						? '<?php var_dump($' . $argsArray[0] . '); ?>'
						: '<?php var_dump(' . $argsArray[0] . '); ?>';;
					}else{
						$data[$dir] = '';
					}
				}
			}
		}

		return $data;
	}
}