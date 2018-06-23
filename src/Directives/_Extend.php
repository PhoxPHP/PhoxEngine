<?php
/**
* @author 		Peter Taiwo <peter@phoxphp.com>
* @package 		Kit\PhoxEngine\Directives\_Extend
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
use Kit\PhoxEngine\{Renderer, Repository};
use Kit\PhoxEngine\Exceptions\TagNotFoundException;
use Kit\PhoxEngine\Directives\Helpers\ExtendHelper;
use Kit\PhoxEngine\Directives\Contract\DirectiveContract;

/*
* Usage:
* ------------------------------------
* Child view
* ------------------------------------
* #parent<"parent_name">
*
* #viewAs<"content">
* 	'view data goes in here'
* #stopView
*
* ------------------------------------
* Parent view
* ------------------------------------
* #contain<"content">
*/

class _Extend implements DirectiveContract
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
		$content = file_get_contents($view);

		ExtendHelper::attach($view);
		$getQueuedData = ExtendHelper::getQueuedData();

		if (!empty($getQueuedData)) {
			foreach($getQueuedData as $i => $key) {
				if (isset($getQueuedData[$i]['parent'])) {

					$data = $this->stackData(
						$getQueuedData[$i]['parent'],
						$getQueuedData[$i]['content']
					);
				
				}else{
					$data = $getQueuedData[$i]['content'];
				}
			
			}
		}

		return $data;
	}

	/**
	* Generates and returns parent and child view data.
	*
	* @param 	$parent <Array>
	* @param 	$child <Array>
	* @access 	protected
	* @return 	<Mixed>
	*/
	protected function stackData($parent, $child)
	{
		$tags = [];
		$parentFile = key($parent);
		$parentContent = $parent[$parentFile]['content']; 
		$parent = current($parent)['content'];
		$data = null;
		$replacement = [];

		// Searches for *contain* tag in the parent layout and queues the tags in the
		// tag variable.
		if (preg_match_all(Attr::EXTEND_CONTAIN_REGEX, $parent, $matches)) {
			foreach($matches[0] as $i => $match) {
				$definedBlockKey = $matches[2][$i]; // block key
				$definedContainKey = $matches[0][$i];
				$tags[$definedBlockKey] = ['_' => $parent, 'key' => $definedContainKey];
			}
		}

		if (sizeof($tags) > 0) {
			foreach(array_keys($tags) as $i => $key) {
				// If a key in the parent view is not defined in the child view,
				// an exception will be returned as all keys present in the child view must also be
				// in the parent view.
				if (!isset($child[$key])) {
					throw new TagNotFoundException(
						sprintf(
							'Parent tag %s does not exist.',
							$key
						)
					);
				}

				// Queue output to be replaced
				$replacement[$tags[$key]['key']] = $child[$key]['data']; 
			}
		}

		$data = str_replace(array_keys($replacement), array_values($replacement), $parent);
		return $data;
	}

}