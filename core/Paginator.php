<?php

namespace Core;

class Paginator
{
    protected $data;
    protected $search;
    protected $search_columns;

    public function paginate($data, $search = [], $search_columns = [])
    {
        $this->data = $data;

        // Keresési paraméterek ellenőrzése
        if (!empty($search) && !empty($search_columns)) {
            $this->search = $search;
            $this->search_columns = $search_columns;

            return $this;
        }

        $itemsPerPage = 5;
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $totalRecords = count($this->data);
        $totalPages = ceil($totalRecords / $itemsPerPage);
        $startIndex = ($currentPage - 1) * $itemsPerPage;
        $dataForPage = array_slice($this->data, $startIndex, $itemsPerPage);

        return [
            'data' => $dataForPage,
            'total_records' => $totalRecords,
            'total_pages' => $totalPages,
            'current_page' => $currentPage,
            'items_per_page' => $itemsPerPage,
        ];
    }

    // Szűrés végrehajtása
    public function filter()
    {
        // Globális keresés
        if (is_string($this->search) && !empty($this->search)) {
            $this->searchGlobal();
        } else {
            $this->search();
        }
    }

    // Globális keresés
    private function searchGlobal()
    {
        $filtered = array_filter($this->data, function($item) {
            foreach ($this->search_columns as $column) {
                if (isset($item->$column) && strpos(strtolower($item->$column), strtolower($this->search)) !== false) {
                    return true;
                }
            }
            return false; // Ha nem találunk egyezést
        });

        // Debugolás: Ellenőrizheted, hogy mi van a szűrt eredményekben
        dd($filtered);
    }

    // Jövőbeni specifikus keresési logika (ha szükséges)
    private function search()
    {
        // Itt implementálhatod a további szűrőfeltételeket, ha szükséges
    }
}
?>
