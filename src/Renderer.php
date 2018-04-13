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
* @package 	Kit\PhoxEngine\Renderer
*/

namespace Kit\PhoxEngine;

use RuntimeException;
use Kit\PhoxEngine\Compiler;
use Kit\PhoxEngine\Directives\_Extend;
use Kit\PhoxEngine\Contracts\{RepositoryContract, RendererContract};

class Renderer implements RendererContract
{

	/**
	* @var 		$repository
	* @access 	protected
	*/
	protected 	$repository;

	/**
	* @var 		$data
	* @access 	protected
	*/
	protected 	$data;

	/**
	* {@inheritDoc}
	*/
	public function __construct(RepositoryContract $repository)
	{
		$this->repository = $repository;
	}

	/**
	* {@inheritDoc}
	*/
	public function render()
	{
		$systemModules = $this->repository->getOpt('system_modules');
		$view = $this->repository->getViewWithExtension();

		if (gettype($view) !== 'string') {
			throw new RuntimeException(sprintf('Cannot render any vew. No view provided.'));
		}

		if (!file_exists($view)) {
			throw new RuntimeException(sprintf('View {%s} does not exist.', $view));
		}

		$output = null;
		
		$extend = new _Extend($this, $this->repository);
		$output .= $extend->getCompiledMixin();

		foreach($systemModules as $module) {
			if ($module !== Kit\PhoxEngine\Directives\_Extend::class) {
				$module = new $module($this, $this->repository);
				$output .= $module->getCompiledMixin();
			}
		}

		echo $output;
	}

	/**
	* @access 	protected
	* @return 	Mixed
	*/
	protected function pushData($data)
	{

	}

}