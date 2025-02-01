<?php

/* use App\Models\Model;

echo '<pre>';
function populate($data)
{

  $populated = array_reduce($data, function ($buffer, $item) {
    // If the id doesn't exist, initialize it with name
    if (!isset($buffer[$item['id']])) {
      $buffer[$item['id']] = [
        'name' => $item['name'],
        'note_body' => [] // Initialize an empty array for post titles
      ];
    }

    // Append post_title to the post_titles array for that id
    $buffer[$item['id']]['note_body'][] = $item['note_body'];
    

    return $buffer;
  }, []);

  dd($populated);
}

$results = (new Model())->leftJoin(
  "SELECT users.id, users.name, posts.title AS post_title, notes.body as note_body FROM users",
  [
    ['table' => 'notes', 'on' => 'users.id = notes.user_id'],
    ['table' => 'posts', 'on' => 'notes.id = posts.note_id']
  ],
);



populate($results);
 */