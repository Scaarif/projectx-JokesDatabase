<?php 
namespace Ijdb;

class IjdbRoutes implements \Ninja\Routes //allow passing of class instance despite an interface
{
	//adding a constructor and class variables  - to have a single instance reppig the authors table
	private $authorsTable;
	private $jokesTable; //added for consistency
	private $authentication; //this' the Authentication object - its then returned by the getAuthentication method
	private $categoriesTable;
	private $jokeCategoriesTable;

	public function __construct() {
		include __DIR__.'/../../includes/DatabaseConnection.php';
		
		$this->jokesTable = new \Ninja\DatabaseTable($pdo, 'joketodelete', 'id', 'Ijdb\Entity\Joke', 
			[&$this->authorsTable, &$this->jokeCategoriesTable]);
		$this->authorsTable = new \Ninja\DatabaseTable($pdo, 'author', 'id', '\Ijdb\Entity\Author', 
			[&$this->jokesTable]); //the & makes these variables References - 'shortcuts'-ish.
		$this->authentication = new \Ninja\Authentication($this->authorsTable, 'email','password');
		$this->categoriesTable = new \Ninja\DatabaseTable($pdo, 'category', 'id', 
			'\Ijdb\Entity\Category', [&$this->jokesTable, &$this->jokeCategoriesTable]);
		$this->jokeCategoriesTable = new \Ninja\DatabaseTable($pdo, 'jokecategory', 'categoryid');
	}

	public function getRoutes(): array {
		
		$jokeController = new \Ijdb\Controllers\Joke($this->jokesTable, $this->authorsTable, 
			$this->categoriesTable, $this->authentication);
		$authorController = new \Ijdb\Controllers\Register($this->authorsTable);
		//adding the login controller
		$loginController = new \Ijdb\Controllers\Login($this->authentication);
		$categoryController = new \Ijdb\Controllers\Category($this->categoriesTable);

		$routes = [
			'joke/edit' => ['POST' => ['controller' => $jokeController, 'action' =>'saveEdit'],
			'GET' => ['controller' => $jokeController, 'action' => 'edit'], 'login' => true
			],
			'joke/delete' => ['POST' => ['controller' => $jokeController, 'action' => 'delete'], 
			'login' => true
			],
			'joke/list' => ['GET' => ['controller' => $jokeController, 'action' => 'list']
			],
			'joke/home' => ['GET' => ['controller' => $jokeController, 'action' => 'home'] //empty array key doesn't work - why?
			], 
			//and now authorController related routes 
			'author/register' => ['GET' => ['controller' => $authorController, 'action' => 'registrationForm'], 
			'POST' => ['controller' => $authorController, 'action' => 'registerUser']
			],
			'author/success' => ['GET' => ['controller' => $authorController, 'action' => 'success']
			],
			'author/permissions' => ['GET' => ['controller' => $authorController, 'action' => 'permissions'], 
			'POST' => ['controller' => $authorController, 'action' => 'savePermissions'],
			'login' => true,
			'permissions' => \Ijdb\Entity\Author::EDIT_USER_ACCESS
			],
			'author/list' => ['GET' => ['controller' => $authorController, 'action' => 'list'],
			'login' => true, 
			'permissions' =>  \Ijdb\Entity\Author::EDIT_USER_ACCESS
			],
			//and now loginController related routes
			'login/error' => ['GET' =>['controller' =>$loginController, 'action' => 'error']],
			'login' => ['GET' =>['controller' => $loginController, 'action' => 'loginForm'],
			'POST' => ['controller' => $loginController, 'action' => 'processLogin']
			],
			'login/success' => ['GET' => ['controller' => $loginController, 'action' => 'success']
			], 
			'logout' => ['GET' => ['controller' => $loginController, 'action' => 'logout']
			],
			//CategoryController related routes
			'category/edit' => ['POST' =>['controller' => $categoryController, 'action' => 'saveEdit'],
			'GET' => ['controller' => $categoryController, 'action' => 'edit'], 'login' => true, 
			'permissions' => \Ijdb\Entity\Author::EDIT_CATEGORIES
			], 
			'category/list' => ['GET' => ['controller' => $categoryController, 'action' => 'list'],
			'login' => true, 'permissions' => \Ijdb\Entity\Author::LIST_CATEGORIES
			],
			'category/delete' => ['POST' => ['controller' => $categoryController, 'action' => 'delete'],
			'login' => true, 'permissions' => \Ijdb\Entity\Author::REMOVE_CATEGORIES
			],
			'category/error' => ['GET' => ['controller' => $categoryController, 'action' => 'error']
			]

		];
		return $routes;
	}

	public function getAuthentication(): \Ninja\Authentication {
		/*$authorsTable = new \Ninja\DatabaseTable($pdo, 'author', 'id');
		return new \Ninja\Authentication($authorsTable, 'email', 'password'); */
		return $this->authentication;
	}

	public function checkPermission($permission): bool {
		$user = $this->authentication->getUser();

		if ($user && $user->hasPermission($permission)) {
			return true;
		} else {
			return false;
		}
	}
}