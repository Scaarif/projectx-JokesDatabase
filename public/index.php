<?php 
try{
	/*include __DIR__.'/../classes/EntryPoint.php';
	include __DIR__.'/../classes/IjdbRoutes.php';
*/
	include __DIR__.'/../includes/autoload.php';

	//$route = ltrim(strtok($_SERVER['REQUEST_URI'], '?'), '/');
	$route = $_GET['route'] ?? 'joke/home'; 

	//$entryPoint = new EntryPoint($route, new IjdbRoutes());
	//$entryPoint = new \Ninja\EntryPoint($route, new \Ijdb\IjdbRoutes()); //namespacing!

	$entryPoint = new \Ninja\EntryPoint($route, $_SERVER['REQUEST_METHOD'], new \Ijdb\IjdbRoutes());

	$entryPoint->run();
} catch (PDOException $e) {
	$title = 'An error has occured';

	$output = 'Database error: '.$e->getMessage().' in '.$e->getFile().':'.$e->getLine();

	include __DIR__.'/../templates/base.html.php';
}