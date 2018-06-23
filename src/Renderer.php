<?php
/**
* @author 		Peter Taiwo <peter@phoxphp.com>
* @package 		Kit\PhoxEngine\Renderer
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

use Kit\PhoxEngine\Compiler;
use Kit\PhoxEngine\Cache\Cache;
use Kit\PhoxEngine\Directives\_Extend;
use Kit\PhoxEngine\Exceptions\ViewNotFoundException;
use KitP\PhoxEngine\Exceptions\RequiredFunctionNotFoundException;
use Kit\PhoxEngine\Contracts\{RepositoryContract, RendererContract};
use Kit\PhoxEngine\Directives\Filter\Repository as FiltersRepository;

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
	public function render(Bool $returnOutput=false, String $view=null, RepositoryContract $repository=null)
	{
		if ($repository == null) {
			$repository = $this->repository;
		}

		$systemModules = $repository->getOpt('system_modules');
		if ($view == null) {
			$view = $repository->getViewWithExtension();
		}

		if (gettype($view) !== 'string') {
			throw new ViewNotFoundException(sprintf('Cannot render any vew. No view provided.'));
		}

		if (!file_exists($view)) {
			throw new ViewNotFoundException(sprintf('View {%s} does not exist.', $view));
		}

		$cache = new Cache(
			$repository,
			$view,
			$returnOutput
		);

		// Is this view cached? If true, load from cache store.
		if ($cache->isEnabled() && $cache->isCached()) {
			return $cache->loadViewFromCache($repository->getVariables());
		}

		$output = [];
		$parsedOutput = file_get_contents($view);
		$extend = new _Extend($this, $repository);
		if ($returnOutput !== true) {
			$parsedOutput = $extend->getCompiledMixin();
			if ($parsedOutput == null) {
				$parsedOutput = file_get_contents($view);
			}
		}
		$rpm = [];

		foreach($systemModules as $module) {
			$module = new $module($this, $repository);
			$output[] = $module->getCompiledMixin($parsedOutput);
		}
		
		foreach($output as $out) {
			if ($out !== null) {
				foreach($out as $i => $key) {
					$rpm[$i] = $key;
				}
			}
		}

		$output = str_replace(
			array_keys($rpm),
			array_values($rpm),
			$parsedOutput
		);

		if (!function_exists('md5')) {
			throw new RequiredFunctionNotFoundException('md5 function is required.');
		}

		if ($cache->isEnabled()) {
			$cache->cacheView(
				$output
			);
		}

		$variables = $repository->getVariables();
		foreach(array_keys($variables) as $key) {
			$value = $variables[$key];
			$$key = $value;
		}

		$output = html_entity_decode($output);
		$__repoClass = $repository->getOpt('filterRepository');
		$__repo = new $__repoClass();

		ob_start();
		eval("?> $output <?php ");
		$data = ob_get_contents();
		ob_end_clean();

		if ($returnOutput == true) {
			return $data;
		}

		echo $data;
	}

}