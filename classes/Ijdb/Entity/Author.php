<?php 

namespace Ijdb\Entity;

class Author 
{
	const EDIT_JOKES = 1;
	const DELETE_JOKES = 2;
	const LIST_CATEGORIES = 4;
	const EDIT_CATEGORIES = 8;
	const REMOVE_CATEGORIES =16;
	const EDIT_USER_ACCESS =32;

	public $id;
	public $name;
	public $email;
	public $password;
	public $permissions;

	public function __construct(\Ninja\DatabaseTable $jokesTable) {
		$this->jokesTable = $jokesTable;
	}

	public function getJokes() {
		return $this->jokesTable->find('authorid', $this->id);
	}

	public function addJoke($joke) {
		$joke['authorid'] = $this->id;
		return $this->jokesTable->save($joke);
	}

	public function hasPermission($permission) {
		return $this->permissions & $permission; //this' bitwise operation - is $permissions bit set in permissions?
	}

//This' an entity class - a class designed to map directly to a record in the database.
//The sole purpose of an entity class is to make some data available.

}

