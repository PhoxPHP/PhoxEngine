<?php
/**
* @author 		Peter Taiwo <peter@phoxphp.com>
* @package 		Kit\PhoxEngine\Directives\_Include
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

namespace Kit\PhoxEngine\Directives;

use Kit\PhoxEngine\Attr;
use Kit\PhoxEngine\Variable;
use Kit\PhoxEngine\{Renderer, Repository};
use Kit\PhoxEngine\Directives\Helpers\ExtendHelper;
use Kit\PhoxEngine\Exceptions\FileNotFoundException;
use Kit\PhoxEngine\Directives\Contract\DirectiveContract;

/*
* Usage:
* #include<"template_name">
*/

class _Include implements DirectiveContract
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
		$data = [];

		if (preg_match_all(Attr::INCLUDE_REGEX, $parsed, $matches)) {
			for($i = 0; $i < count($matches[0]); $i++) {

				$dir = $matches[0][$i];
				$template = str_replace('"', "", $matches[1][$i]);

				$repository = new Repository();
				$repository->setView($template);

				$view = $repository->getViewWithExtension();
				if (!file_exists($view)) {
					throw new FileNotFoundException(
						sprintf(
							'File %s does not exist',
							$view
						)
					);
				}

				$renderer = new Renderer($repository);
				$output = $renderer->render(true, $view);

				$data[$dir] = $output;
			}
		}

		return $data;
	}
}