<?php 

namespace Ijdb\Entity;

class Joke 
{
	public $id;
	public $authorid;
	public $dateadded;
	public $joke;
	private $author; //to store the author between method calls - implementing Transparent Caching! 
	private $authorsTable;
	private $jokeCategoriesTable;

	public function __construct(\Ninja\DatabaseTable $authorsTable, 
		\Ninja\DatabaseTable $jokeCategoriesTable) {
		$this->authorsTable = $authorsTable;
		$this->jokeCategoriesTable = $jokeCategoriesTable;
	}

	public function getAuthor() {
		if (empty($this->author)) {
			$this->author = $this->authorsTable->findById($this->authorid);
		}
		return $this->author;
	}

	public function addCategory($categoryid) {
		$jokeCat = ['jokeid' => $this->id, 'categoryid' => $categoryid];
		$this->jokeCategoriesTable->save($jokeCat);
	}

	public function hasCategory($categoryid) {
		$jokeCategories = $this->jokeCategoriesTable->find('jokeid', $this->id);

		foreach ($jokeCategories as $jokeCategory) {
			if ($jokeCategory->categoryid == $categoryid) {
				return true;
			}
		}
	}

	public function clearCategories() {
		$this->jokeCategoriesTable->deleteWhere('jokeid', $this->id);
	}


}