<?php 
namespace Ninja;

class Authentication 
{
	private $users;
	private $usernameColumn;
	private $passwordColumn;

	public function __construct(DatabaseTable $users, $usernameColumn, $passwordColumn) {
		session_start();
		$this->users = $users;
		$this->usernameColumn = $usernameColumn;
		$this->passwordColumn = $passwordColumn;
	}

	public function login($username, $password) {
		$user = $this->users->find($this->usernameColumn, strtolower($username));

		if (!empty($user) && password_verify($password, $user[0]->{$this->passwordColumn})) {
			session_regenerate_id();
			$_SESSION['username'] = $username;
			$_SESSION['password'] = $user[0]->{$this->passwordColumn};
			return true;
		} else {
			return false;
		}
	}

	public function isLoggedIn() {
		if (empty($_SESSION['username'])) {
			return false;
		}
		$user = $this->users->find($this->usernameColumn, strtolower($_SESSION['username']));

		/*if (!empty($user) && $user[0][$this->passwordColumn] === $_SESSION['password']) { */
		$passwordColumn = $this->passwordColumn; //create the variable so it's value is read first (PHP evaluates from left to right. therefore, $user[0]->$this->passwordColumn would give a error
		//the same sould be achieved by using braces: $user[0]->{$this->passwordColumn} - this tells PHP to evaluate the variable first.
		if (!empty($user) && $user[0]->$passwordColumn === $_SESSION['password']) {
			return true;
		} else {
			return false;
		}
	}

	public function getUser(){
		if ($this->isLoggedIn()) {
			return $this->users->find($this->usernameColumn, strtolower($_SESSION['username']))[0];
		}
		else {
			return false;
		}
	}
}