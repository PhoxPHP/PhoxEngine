<?php
namespace Kit\PhoxEngine\Contracts;

use Kit\PhoxEngine\Contracts\RepositoryContract;

interface RendererContract
{

	/**
	* Renderer constructor.
	*
	* @param 	$repository <Kit\PhoxEngine\Contract\Repository>
	* @access 	public
	* @return 	void
	*/
	public function __construct(RepositoryContract $repository);

	/**
	* Renders data and returns the output.
	*
	* @param 	$returnOutput <Boolean> If output should be returned or rendered.
	* @param 	$view <String> View to be rendered.
	* @param 	$repository <Object>
	* @access 	public
	* @return 	String
	*/
	public function render(Bool $returnOutput=false, String $view=null, RepositoryContract $repository=null);

}