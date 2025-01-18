<?php
/*  // LEFT JOIN notes ON notes.user_id = users.id
  public function getWithLeftJoin($selects, $table, $join_table, $id_name, $join_table_id_name)
  {
    // Append LEFT JOIN to the query
    $results = $this->db->query("SELECT $selects FROM $table LEFT JOIN $join_table on $table.$id_name = $join_table_id_name")->get();

    $data = $this->populate($results, ['notes' => ['note_idx0']]);

    return $data;
  } 
    
    private function populate($res, $joinFields = [])
  {
    $ret = [];

    foreach ($res as $key => $row) {
      if (!isset($ret[$row->id])) {
        // Ha nem létezik a kulcs, hozzuk létre a megfelelő objektummal
        $ret[$row->id] = (array) $row; // Átalakítjuk objektummá
      }

      // Iterálunk a megadott join mezőkön és hozzárendeljük őket
      foreach ($joinFields as $joinKey => $joinField) {
        // Ha az adott mező létezik az objektumban, hozzáadjuk
        if (isset($row->$joinField)) {
          // Ha az adat már létezik, akkor hozzáadjuk a már meglévő tömbhöz
          if (!isset($ret[$row->id][$joinKey])) {
            $ret[$row->id][$joinKey] = [];
          }

          // Az aktuális mezőt hozzáadjuk a megfelelő join key alá
          $ret[$row->id][$joinKey][] = $row->$joinField;
        }
      }
    }

    return $ret; // Kiíratjuk a struktúrát
  }

  $this->storage->save()
  
  */