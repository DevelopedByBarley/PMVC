<?php

namespace Core;

/* How To use?
		//Paginate all passed data
 		$paginated = (new Paginator)
    	->data($users)
    	->paginate();
		
		//Paginate simple by single string from $_GET['search] and filter by array ['name', 'email'];
    $paginated = (new Paginator)
    	->data($users)
    	->filter($_GET['search'] ?? '', ['name', 'email'])
    	->paginate();
	
			//Paginate from $GET method and filter by array ['name', 'email']
			$paginated = (new Paginator)
    ->data($users)
    ->filter($_GET ?? [], ['name', 'email', 'phone'])
    ->paginate();

*/

class Paginator
{
	protected $data;
	protected $search;
	protected $search_columns;

	public function data($data)
	{

		$this->data = $data;

		return $this;
	}

	public function paginate($itemsPerPage = 25)
	{

		$currentPage = isset($_GET['offset']) ? (int)$_GET['offset'] : 1;
		$totalRecords = count($this->data);
		$totalPages = ceil($totalRecords / $itemsPerPage);
		$startIndex = ($currentPage - 1) * $itemsPerPage;
		$dataForPage = array_slice($this->data, $startIndex, $itemsPerPage);

		return (object)[
			'data' => $dataForPage,
			'total_records' => $totalRecords,
			'total_pages' => $totalPages,
			'current_page' => $currentPage,
			'items_per_page' => $itemsPerPage,
		];
	}

	// Szűrés végrehajtása
	public function filter($search, $search_columns)
	{
		$this->search = $search;
		$this->search_columns = $search_columns;
		

		if (empty($this->search)) return $this;



		if (is_string($this->search) && !empty($this->search)) {
			$this->searchGlobal();
		} else {
			$this->search();
		}

		return $this;
	}

	// Globális keresés
	private function searchGlobal()
	{
		$filtered = array_filter($this->data, function ($item) {
			foreach ($this->search_columns as $column) {
				if (isset($item->$column) && strpos(strtolower($item->$column), strtolower($this->search)) !== false) {
					return true;
				}
			}
			return false; // Ha nem találunk egyezést
		});

		// Debugolás: Ellenőrizheted, hogy mi van a szűrt eredményekben
		$this->data = $filtered;
		return $this;
	}


	// Meg kell keresni 
	private function search()
	{
		$search_columns = $this->search_columns;
		$search = $this->search;

		$filtered = array_filter($this->data, function ($row) use ($search_columns, $search) {
			foreach ($search_columns as $column) {
				$value = isset($search[$column]) ? trim((string)$search[$column]) : '';
				$rowValue = isset($row->{$column}) ? trim(strtolower((string)$row->{$column})) : null;
				$searchValue = trim(strtolower($value));

				if (!empty($searchValue)) {
					if (is_null($rowValue) || strpos($rowValue, $searchValue) === false) {
						return false;
					}
				}
			}
			return true;
		});


		$this->data = $filtered; // Szűrt adatok beállítása
	}
}
