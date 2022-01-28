<?php 
namespace Ijdb\Controllers;
use \Ninja\DatabaseTable; //importing another namespace into this namespace for use in the construct fn
use \Ninja\Authentication;

class Joke 
{
	private $authorsTable;
	private $jokesTable;
	private $categoriesTable;
	private $authentication;

	public function __construct(/*\Ninja\DatabaseTable $jokesTable, \Ninja\DatabaseTable $authorsTable*/ DatabaseTable $jokesTable, DatabaseTable $authorsTable, DatabaseTable $categoriesTable, 
		Authentication $authentication){
		$this->jokesTable = $jokesTable;
		$this->authorsTable = $authorsTable;
		$this->authentication = $authentication;
		$this->categoriesTable = $categoriesTable;
	}

	public function list() {
		//supply the offset
		$page = $_GET['page'] ?? 1;
		$offset = ($page-1)*10; //i.e page 1 offsets 0 jokes, 2 - 10 jokes, 3 - 20 jokes e.t.c 

		if (isset($_GET['category'])) {
			$category = $this->categoriesTable->findById($_GET['category']);
			$jokes = $category->getJokes(10, $offset);
			$totalJokes = $category->getNumJokes();
		} else {
		$jokes = $this->jokesTable->findAll('dateadded DESC', 10, $offset);
		$totalJokes = $this->jokesTable->total();
		}

		/*$jokes = [];
		foreach ($result as $joke) {
			/*$author = $this->authorsTable->findById($joke['authorid']);
			$jokes[] = [
				'id' => $joke['id'],
				'joke' => $joke['joke'],
				'dateadded' => $joke['dateadded'],
				'name' => $author['name'],
				'email' => $author['email'],
				'authorid' => $author['id']
			]; */
			/*$author = $this->authorsTable->findById($joke->authorid);
			$jokes[] = [
				'id' => $joke->id,
				'joke' => $joke->joke,
				'dateadded' => $joke->dateadded,
				'name' => $author->name,
				'email' => $author->email,
				'authorid' => $author->id
			];
		}
		*/

		$title = 'Joke List';

		//pass the id of the loggedIn user to the template
		$author = $this->authentication->getUser();

		return ['template' => 'jokes.html.php', 'title' => $title, 'variables' =>['totalJokes'=>$totalJokes, 'jokes' => $jokes, 'user' => $author ?? null, //as the viewer may not be logged in, there might not be a userId hence the null option! 
		'categories' => $this->categoriesTable->findAll(), 'currentPage' => $page, 
		'category' => $_GET['category'] ?? null ] 
		];
	}

	public function home() {
		$title = 'Internet Joke Database';

		return ['template' => 'home.html.php', 'title' => $title];
	}

	public function delete() {
		//To prevent someone from creating a form to delete other pple's jokes:
		$author = $this->authentication->getUser();
		$joke = $this->jokesTable->findById($_POST['id']);

		if ($joke->authorid != $author->id && !$author->hasPermission(\Ijdb\Entity\Author::DELETE_JOKEs)) {
			return;
		}

		//else, its safe to delete...

		$this->jokesTable->delete($_POST['id']);
		header('location: index.php?route=joke/list');
	}

	public function saveEdit() {
		//$author = $this->authentication->getUser(); //returns an array
		$author = $this->authentication->getUser(); //returns an object ...

		/*//verify that the existing joke's authorid matches the id of the logged in user
		if (isset($_GET['id'])) {
			$joke = $this->jokesTable->findById($_GET['id']);
			if ($joke['authorid'] != $author['id']) {
				return; //don't allow a non-owner to edit a joke!
			}
		}

		$joke = $_POST['joke']; //directly copies the values from the form on submission
		$joke['dateadded'] = new \DateTime(); //append the missing values
		//$joke['authorid'] = 1;
		$joke['authorid'] = $author['id']; //i.e save a new joke with the loggedIn user's id.

		$this->jokesTable->save($joke);
		*/
		/*$authorObject = new \Ijdb\Entity\Author($this->jokesTable);
		$authorObject->id = $author['id'];
		$authorObject->name = $author['name'];
		$authorObject->email = $author['email'];
		$authorObject->password = $author['password']; 
		*/

		$joke = $_POST['joke']; //directly copies the values from the form on submission
		$joke['dateadded'] = new \DateTime(); //append the missing values

		$jokeEntity = $author->addJoke($joke);

		$jokeEntity->clearCategories();

		foreach ($_POST['category'] as $categoryid) { //assign categories to a joke instance
			$jokeEntity->addCategory($categoryid);
		}

		header('location: index.php?route=joke/list');
	} 

	public function edit() {
		$author = $this->authentication->getUser();
		$categories = $this->categoriesTable->findAll();

		if (isset($_GET['id'])){
			$joke = $this->jokesTable->findById($_GET['id']);
		}

		$title = 'Edit joke';
		
		return ['template'=>'editjoke.html.php', 'title' => $title, 
		'variables' =>['joke' =>$joke ?? null, 'user' => $author ?? null, 'categories' => 
		$categories]
		];
	}

}
