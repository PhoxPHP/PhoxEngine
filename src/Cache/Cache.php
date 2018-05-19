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
* @package 	Kit\PhoxEngine\Cache\Cache
*/

namespace Kit\PhoxEngine\Cache;

use Kit\PhoxEngine\Contracts\RepositoryContract;

class Cache
{

	/**
	* @var 		$file
	* @access 	protected
	*/
	protected 	$file;

	/**
	* @var 		$repository
	* @access 	protected
	*/
	protected 	$repository;

	/**
	* @var 		$viewId
	* @access 	protected
	*/
	protected 	$viewId;

	/**
	* @var 		$cachePath
	* @access 	protected
	*/
	protected 	$cachePath;

	/**
	* @var 		$cacheEnabled
	* @access 	protected
	*/
	protected 	$cacheEnabled;

	/**
	* @var 		$returnOutput
	* @access 	protected
	*/
	protected 	$returnOutput = false;

	/**
	* Cache constructor.
	*
	* @param 	$repository <Ki\PhoxEngine\Contracts\RepositoryContract>
	* @param 	$file <String>
	* @param 	$returnOutput <Boolean>
	* @access 	public
	* @return 	void
	*/
	public function __construct(RepositoryContract $repository, String $file, Bool $returnOutput=false)
	{
		$this->repository = $repository;
		$this->file = $file;
		$this->viewId = md5($file);
		$cacheConfig = $this->repository->getOpt('cache');
		$this->cachePath = $cacheConfig['path'];
		$this->cacheEnabled = $this->repository->getOpt('enable_caching');
		$this->returnOutput = $returnOutput;
	}

	/**
	* Checks if caching is enabled.
	*
	* @access 	public
	* @return 	Boolean
	*/
	public function isEnabled() : Bool
	{
		if ($this->cacheEnabled == true) {
			return true;
		}

		return false;
	}

	/**
	* Checks if a view is cached.
	*
	* @access 	public
	* @return 	Boolean
	*/
	public function isCached() : Bool
	{
		$cacheFile = $this->cachePath . $this->viewId . '.cache';

		if (file_exists($cacheFile)) {
			return true;
		}

		return false;
	}

	/**
	* Caches a view.
	*
	* @param 	$content <String>
	* @access 	public
	* @return 	Boolean
	*/
	public function cacheView(String $content)
	{
		$cacheFile = $this->cachePath . $this->viewId . '.cache';

		if (file_exists($cacheFile)) {
			unlink($cacheFile);
		}

		$fileHandle = fopen($cacheFile, 'w');
		fwrite($fileHandle, $content);
		fclose($fileHandle);
		return true;
	}

	/**
	* Loads a view from cache.
	*
	* @param 	$variables <Array>
	* @access 	public
	* @return 	String
	*/
	public function loadViewFromCache(Array $variables=[])
	{
		foreach(array_keys($variables) as $key) {
			$var = $variables[$key];
			$$key = $var;
		}

		$cacheFile = $this->cachePath . $this->viewId . '.cache';
		$output = file_get_contents($cacheFile);
		$output = html_entity_decode($output);

		$__repoClass = $this->repository->getOpt('filterRepository');
		$__repo = new $__repoClass();

		ob_start();
		eval("?> $output <?php ");
		$data = ob_get_contents();
		ob_end_clean();

		if ($this->returnOutput == true) {
			return $data;
		}

		echo $data;
	}

}