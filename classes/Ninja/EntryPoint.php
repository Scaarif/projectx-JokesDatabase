<?php 
namespace Ninja;

class EntryPoint 
{
	private $route;
	private $method;
	private $routes;

	public function __construct(string $route, string $method, \Ninja\Routes $routes) { 
		//the type hint \Ninja\Routes would have been \Ijdb\IjdbRoutes - inflexible since specific!
		$this->route = $route;
		$this->routes = $routes; //contains an instance of the IjdbRoutes class
		$this->method = $method;
		$this->checkUrl();
	}

	private function checkUrl() {
		if ($this->route !== strtolower($this->route)) {
			http_response_code(301);
			header('location: '.strtolower($this->route));
		}
	}

	private function loadTemplate($templateFileName, $variables = []) {
		extract($variables);

		ob_start();
		include __DIR__.'/../../templates/'.$templateFileName;
		return ob_get_clean();
	}

	public function run() {
		$routes = $this->routes->getRoutes();
		$authentication = $this->routes->getAuthentication();

		//adding a check that looks for the login key in the route array
		if (isset($routes[$this->route]['login']) && isset($routes[$this->route]['login']) && 
			!$authentication->isLoggedIn()) {
			header('location: index.php?route=login/error'); //this' a problem probably ? 
		} 
		else if (isset($routes[$this->route]['permissions']) && !$this->routes->checkPermission(
			$routes[$this->route]['permissions'])) {
			header('location: index.php?route=category/error'); //check that if there is a permissions key set in  particular route(or page), that access to that route is denied if they don't posess said permissions 
		}
		else {
			
			$controller = $routes[$this->route][$this->method]['controller'];
			$action = $routes[$this->route][$this->method]['action'];

			$page = $controller->$action();

			$title = $page['title'];

			if (isset($page['variables'])) {
				$output = $this->loadTemplate($page['template'], $page['variables']);
			} else {
				$output = $this->loadTemplate($page['template']);
			}
			//include __DIR__.'/../../templates/base.html.php';
			//replacing the line above with the following - based on the logged in status of a user
			echo $this->loadTemplate('base.html.php', ['loggedIn' => $authentication->isLoggedIn(), 
				'output' => $output, 'title' => $title]);
		}
	}
	
}

/*This class is now completely generic - callAction method, which was specific to the current data was moved, making this a valid Framework!*/
