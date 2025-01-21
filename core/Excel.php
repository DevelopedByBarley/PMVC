<?php

namespace Core;

use Shuchkin\SimpleXLSXGen;

class Excel
{
  private $data = [];
  private $filteredData = [];

  /**
   * Beállítja az adatokat
   */
  public function data($data)
  {
    $this->data = $data;
    $this->filteredData = $data; // Kezdetben az eredeti adatokkal dolgozunk
    return $this;
  }

  public function except($exceptions = [])
  {
    if (!$exceptions) {
      return $this;
    }

    $this->filteredData = array_map(function ($row) use ($exceptions) { // Map on $this->data, using exceptions
       return array_filter($row,function ($key) use ($exceptions) { // Filter array where $key in $exceitions array
          return !in_array($key, $exceptions);
        },
        ARRAY_FILTER_USE_KEY // Use key for filter
      ); 
    }, $this->data); // Pass the data here

    //Return filtered data
    return $this;
  }


  public function download($filename = 'data.xlsx')
  {
    $path = base_path('resources/documents/' . $filename);
    $this->generateExcel()->downloadAs($path);
  }

  public function write($path)
  {
    $this->generateExcel()->saveAs($path);
  }


  // Really i dont understand what happening here, AI generated
  private function generateExcel()
  {
    if (empty($this->filteredData)) {
      throw new \Exception("Nincsenek adatok a fájl generálásához.");
    }

    $headers = array_keys($this->filteredData[0]);
    $data = array_merge([$headers], array_map('array_values', $this->filteredData));

    return SimpleXLSXGen::fromArray($data);
  }
}
