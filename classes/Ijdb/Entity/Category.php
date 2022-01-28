<?php 
namespace Ijdb\Entity;

use Ninja\DatabaseTable;

class Category 
{
	public $id;
	public $name;
	private $jokesTable;
	private $jokeCategoriesTable;

	public function __construct(DatabaseTable $jokesTable, DatabaseTable $jokeCategoriesTable) {
		$this->jokesTable = $jokesTable;
		$this->jokeCategoriesTable = $jokeCategoriesTable;
	}

	public function getJokes($limit = null, $offset = null) {
		$jokeCategories = $this->jokeCategoriesTable->find('categoryid', $this->id, null, $limit, 
		$offset);// all jokeid's.
		$jokes = [];

		foreach ($jokeCategories as $jokeCategory) {
			$joke = $this->jokesTable->findById($jokeCategory->jokeid);
			if ($joke) {
				$jokes[] = $joke;
			}
		}
		//sort the jokes array
		usort($jokes, [$this, 'sortJokes']); //takes array to sort and the comparison fn as arguments

		return $jokes;
	}

	private function sortJokes($a, $b) {
		$aDate = new \DateTime($a->dateadded);
		$bDate = new \DateTime($b->dateadded); //convert the dates into datetime instances

		if ($aDate->getTimestamp() == $bDate->getTimestamp()) {
			return 0; //i.e none of the two values is greater than the other...ordering is same
		}
		return $aDate->getTimestamp() > $bDate->getTimestamp() ? -1 : 1; //return -1 if $a > $b else return 1 - in former case $a comes before $b and vise versa in the latter case.
	}

	public function getNumJokes() {
		return $this->jokeCategoriesTable->total('categoryid', $this->id);
	}
}