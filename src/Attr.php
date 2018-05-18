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
* @package 	Kit\PhoxEngine\Attr
*/

namespace Kit\PhoxEngine;

abstract class Attr
{

	/**
	* Extend directive contain regular expression.
	*/
	const EXTEND_CONTAIN_REGEX = "/\#contain\<(\'|\")(.*?)(\'|\")\>/";

	/**
	* Extend directive parent regular expression.
	*/
	const EXTEND_PARENT_REGEX = "/\#parent\<(\'|\")(.*?)(\'|\")\>/";

	/**
	* Extend directive viewAs regular expression.
	*/
	const EXTEND_VIEWAS_REGEX = "/\#viewAs\<(\'|\")(.*?)(\'|\")\>(.*?)\#stopView/s";

	/**
	* Each directive regular expression.
	*/
	const EACH_REGEX = "/\#each\<(.*?)\>(.*?)(\#stopEach)/s";

	/**
	* Setter directive regular expression
	*/
	const SETTER_REGEX = "/\#set\<(.*?), (.*?)\>/s";

	/**
	* Getter echo directive regular expression
	*/
	const GETTER_ECHO_REGEX = "/\#\{(.*?)\}/s";

	/**
	* If directive regular expression
	*/
	const IF_REGEX = "/\#if\<(.*)\>/";

	/**
	* Else directive regular expression
	*/
	const ELSE_REGEX = "/\#else/s";

	/**
	* If directive regular expression
	*/
	const ELSE_IF_REGEX = "/\#elseif\<(.*?)\>/";

	/**
	* Raw directive regular expression
	*/
	const RAW_REGEX = "/\#php\<(.*?)\>/s";

	/**
	* Include directive regular expression
	*/
	const INCLUDE_REGEX = "/\#include\<(.*?)\>/s";

	/**
	* Cookie directive regular expression
	*/
	const COOKIE_REGEX = "/\#cookie\<(.*?)\>/s";

}