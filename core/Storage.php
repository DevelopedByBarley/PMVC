<?php

namespace Core;


/* 
  Upload single file
  (new Storage())->file($_FILES['file'])->save("/");
  (new Storage())->file($_FILES['file'])->deletePrevImages('/', ['886381635678c00b413fa04.94287000.jpg', '1051344980678c0041199644.67240229.png'])->save('/');
  
  Upload multiple files
  (new Storage())->files($_FILES['file'])->deletePrevImages('/', ['886381635678c00b413fa04.94287000.jpg', '1051344980678c0041199644.67240229.png'])->save('/');
*/

class Storage
{
  private array $whiteList;
  public $files = null;
  public $file;


  public function __construct(?array $whiteList = null)
  {
    $config = require base_path('config/storage.php');

    // Ha nincs megadva, az alapértelmezett fehér lista értékét a konfigurációból veszjük
    $this->whiteList = $whiteList ?? $config['white_list'];
  }


  // Add single file for uplodat,
  public function file($file)
  {
    if (empty($file['name'][0])) {
      return $this;
    }
    $this->file = $file;
    $this->checkMimeType($this->file);
    return $this;
  }

  private function formatFilesForSave($files)
  {
    $ret = [];

    foreach ($files as $fieldName => $fields) {

      foreach ($fields as $index => $field) {
        $ret[$index][$fieldName] = $fields[$index];
      }
    }
    return $ret;
  }

  // Add multiple files in array;
  public function files($files)
  {
    if (empty($files['name'][0])) {
      return $this;
    }
    $this->files = $this->formatFilesForSave($files);
    $this->checkMimeTypeByArray($this->files);
    return $this;
  }

  public function deletePrevImages($path, $arr_of_images)
  {
    if (!empty($arr_of_images)) {
      foreach ($arr_of_images as $image) {
        $imagePath = base_path('public/images' . $path . "/" . $image);

        if (file_exists($imagePath)) {
          unlink($imagePath);
        }
      }
    }

    return $this;
  }


  public function save($path = "/")
  {
    if ((!$this->file || is_null($this->file)) && (!$this->files || is_null($this->files))) {
      return null;
    }
    if (!empty($this->files)) {
      foreach ($this->files as $file) {
        $fileNames = $this->saveFile($file, $path);
        return $fileNames;
      }
    } else {
      $fileName = $this->saveFile($this->file, $path);
      return $fileName;
    }
  }

  private function saveFile($file, $path)
  {
    $rand = uniqid(rand(), true);
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $originalFileName = $rand . '.' . $ext;
    $directoryPath = base_path('public/images' . $path);

    if (!is_dir($directoryPath)) {
      mkdir($directoryPath, 0755, true);
    }

    $destination = $directoryPath . '/' . $originalFileName;
    move_uploaded_file($file["tmp_name"], $destination);

    return $originalFileName;
  }


  private function checkMimeType($file)
  {
    $fileType = mime_content_type($file["tmp_name"]);

    if (!in_array($fileType, $this->whiteList)) {
      Log::info('File mimetype is not allowed');
      (new Toast)->danger('Hibás fájl formátum, kérjük próbálja meg újra.')->back();
    }
  }

  private function checkMimeTypeByArray()
  {
    foreach ($this->files as $file) {
      $this->checkMimeType($file);
    }
  }



  public function getWhiteList(): array
  {
    return $this->whiteList;
  }
}
