<?php 
namespace Ijdb\Controllers;

use \Ninja\DatabaseTable;

class Register 
{
	private $authorsTable;

	public function __construct(DatabaseTable $authorsTable) {
		$this->authorsTable = $authorsTable;
	}

	public function registrationForm() {
		return ['template' => 'register.html.php', 'title' => 'Reister an account'];
	} 

	public function success() {
		return ['template' => 'registersuccess.html.php', 'title' => 'Registration Successful'];
	}

	public function registerUser() {
		$author = $_POST['author'];
		//assume that the data is valid to begin with
		$valid = true;
		$errors = [];
		//But if any of the fields have been left blank, set $valid to false
		if (empty($author['name'])) {
			$valid = false;
			$errors[] = 'Name cannot be blank';
		}
		if (empty($author['email'])) {
			$valid = false;
			$errors[] = 'Email cannot be blank';
		} 
		//if email is not blank, check that it is a valid email address
		else if (filter_var($author['email'], FILTER_VALIDATE_EMAIL) == false) {
			$valid = false;
			$errors[] = 'Invalid email address';
		} else {

			//if the email is valid , convert it to lowercase version...
			$author['email'] = strtolower($author['email']);

			//search for the lowercase version of it in the database
			if (count($this->authorsTable->find('email', $author['email'])) > 0) {
				$valid = false;
				$errors[] = 'That email address is already registered';
			}
		}

		if (empty($author['password'])) {
			$valid = false;
			$errors[] = 'Password cannot be blank';
		}
		//If $valid is still true, no fields were blank - the data can now be added to database
		//the $author variable now contains a lowercase version of email - as desired.
		if ($valid == true) {
			//has the password before saving it in the database
			$author['password'] = password_hash($author['password'], PASSWORD_DEFAULT);

			//now save -author with lowercase email and a password hash. Robust and safe-r.
			$this->authorsTable->save($author);

			header('Location: index.php?route=author/success');
		} else {
			//if the data is not valid, show the form again
			return ['template' => 'register.html.php', 'title' => 'Register an account', 
			'variables' =>['errors' => $errors, 'author' => $author ]];
		}
	}

	public function list() {
		$authors = $this->authorsTable->findAll();

		return ['template' => 'authorlist.html.php', 'title' => 'Author List', 'variables' => [
			'authors' => $authors]
		];
	}

	public function permissions() {
		$author = $this->authorsTable->findById($_GET['id']);
		
		$reflected = new \ReflectionClass('\Ijdb\Entity\Author'); //mirrors a class constants, variables and methods - i.e reads this properties from a class
		$constants = $reflected->getConstants();

		return ['template' => 'permissions.html.php', 'title' => 'Edit Permissions', 'variables' => [
			'author' => $author, 'permissions' => $constants]
		];
	}

	public function savePermissions() {
		$author = ['id' => $_GET['id'], 'permissions' => array_sum($_POST['permissions'] ?? [])];
		$this->authorsTable->save($author);

		header('location: index.php?route=author/list');
	}
}