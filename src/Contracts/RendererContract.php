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
	* @access 	public
	* @return 	String
	*/
	public function render();

}