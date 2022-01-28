<?php 
namespace Ninja;

interface Routes 
{
	/*public function getRoutes();
	public function getAuthentication();*/

	//to type hint the return values - an extra line of defence against errors
	public function getRoutes(): array; //this must return an array
	public function getAuthentication(): \Ninja\Authentication; //and this - an Authentication object
	public function checkPermission($permission): bool;
}

//interface files can, just like classes, be loaded by autoloader.
// we've written this for robustness - in typehinting in entrypoint?
// or is it to make these fns available to entrypoint?