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
* @package 	Kit\PhoxEngine\Directives\Helpers\ExtendHelper
*/

namespace Kit\PhoxEngine\Directives\Helpers;

use Kit\PhoxEngine\Attr;
use Kit\PhoxEngine\Config;
use Kit\PhoxEngine\Repository;

abstract class ExtendHelper
{

	/**
	* @var 		$data
	* @access 	protected
	* @static
	*/
	protected static $data = [];

	/**
	* @param 	$file <Mixed>
	* @access 	public
	* @return 	Mixed
	*/
	public static function attach(String $file=null)
	{
		$data = file_get_contents($file);
		if (preg_match_all(Attr::EXTEND_PARENT_REGEX, $data, $match)) {

			ExtendHelper::$data[$file] = [
				'parent' => ExtendHelper::attachRecursive($match[2][0]),
				'raw' => $data,
				'content' => ExtendHelper::pushSectionsContentToArray($match[0][0], $data)
			];
		}
	}

	/**
	* @access 	public
	* @return 	Array
	* @static
	*/
	public static function getQueuedData()
	{
		return ExtendHelper::$data;
	}

	/**
	* Push view sections to an array and returns an array or null.
	*
	* @param 	$parentTag <String>
	* @param 	$rawString <String> 
	* @access 	protected
	* @return 	Mixed
	* @static
	*/
	protected static function pushSectionsContentToArray(String $parentTag, String $rawString)
	{
		$content = str_replace($parentTag, '', $rawString);
		$dataArray = [];

		if (preg_match_all(Attr::EXTEND_VIEWAS_REGEX, $content, $matches)) {
			foreach($matches[0] as $i => $match) {
				$data = [];
				$data['o_quote'] = $matches[1][$i];
				$data['c_quote'] = $matches[3][$i];
				$data['data'] = $matches[4][$i];

				$dataArray[$matches[2][$i]] = $data;
			}
		}

		if (sizeof($dataArray) < 1) {
			$dataArray = null;
		}

		return $dataArray;
	}

	/**
	* @param 	$file <String>
	* @access 	public
	* @return 	Array
	*/
	protected static function attachRecursive(String $file) : Array
	{
		$repository = Repository::getInstance();
		$repository->setView($file);
		$data = file_get_contents($repository->getViewWithExtension());
		$parent = [];

		$file = $repository->getViewWithExtension();
		if (preg_match_all(Attr::EXTEND_PARENT_REGEX, $data, $match)) {

			$parent[$file] = [
				'parent' => ExtendHelper::attachRecursive($match[2][0]),
				'raw' => $data,
				'content' => ExtendHelper::pushSectionsContentToArray($match[0][0], $data)
			];
		}else{
			$parent[$file] = [
				'content' => $data
			];
		}

		return $parent;
	}

}