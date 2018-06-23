<?php
/**
* @author 		Peter Taiwo <peter@phoxphp.com>
* @package 		Kit\PhoxEngine\Directives\Each
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
use InvalidArgumentException;
use Kit\PhoxEngine\{Renderer, Repository};
use Kit\PhoxEngine\Directives\Helpers\ExtendHelper;
use Kit\PhoxEngine\Directives\Contract\DirectiveContract;

/*
* Usage:
* #each<$users : $user : $index>
*	{{ data }}
* #stopEach
*/

class Each implements DirectiveContract
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
	* @var 		$info
	* @access 	protected
	*/
	protected 	$info = [];

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
	public function getCompiledMixin(String $parsedOutput=null)
	{
		$data = [];
		$directiveTags = $this->getDirectiveTags($parsedOutput);

		if (count($directiveTags) > 0) {
			foreach($directiveTags as $directiveTag) {
				$dir = $directiveTag['dir'];
				$content = $directiveTag['dir-content'];
				$loopArgs = $directiveTag['dir-data'];
				$loopArgsArray = explode(':', $loopArgs);

				// if arguments are more than 3 which is the maximum,
				// an exception will be thrown. 
				if (count($loopArgsArray) > 3) {
					throw new InvalidArgumentException(sprintf('Number of each directive arguments exceeds 3.'));
				}

				$valueArg = ($loopArgsArray[1]) ?? "\$value";
				$keyArg = ($loopArgsArray[2]) ?? "\$index";
				$valueArg = trim($valueArg);
				$keyArg = trim($keyArg);

				$varObject = new Variable($this->repository);

				if ($varObject->getVariableType($valueArg) !== 1) {
					throw new InvalidArgumentException(
						sprint(
							'Invalid argument {%s} provided in loop directive.',
							$valueArg
						)
					);
				}

				if ($varObject->getVariableType($keyArg) !== 1) {
					throw new InvalidArgumentException(
						sprint(
							'Invalid argument {%s} provided in loop directive.',
							$keyArg
						)
					);
				}

				$initArg = trim($loopArgsArray[0]);
				$var = new Variable($this->repository, $initArg);
				$arrayExpressionVar = $var->getParsedVariable();
				$content = "<?php foreach($arrayExpressionVar as $keyArg => $valueArg): ?>$content<?php endforeach; ?>";

				$data[$dir] = $content;
			}
		}

		return $data;
	}

	/**
	* Returns an array of directive tags found in the view.
	*
	* @param 	$data <String> View data
	* @access 	protected
	* @return 	<Array>
	*/
	protected function getDirectiveTags(String $data=null) : Array
	{
		$directiveTags = [];
		
		if (preg_match_all(Attr::EACH_REGEX, $data, $matches)) {
			for($i = 0; $i < count($matches[0]); $i++) {
				$directive = $matches[0][$i];
				$directiveData = $matches[1][$i];
				$directiveContent = $matches[2][$i];
				$directiveClosingTag = $matches[3][$i];

				$directiveTags[] = [
					'dir' => $directive,
					'dir-data' => $directiveData,
					'dir-content' => $directiveContent,
					'dir-closing-tag' => $directiveClosingTag
				];
			}
		}

		return $directiveTags;
	}

}