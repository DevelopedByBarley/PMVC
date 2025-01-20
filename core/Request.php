<?php
  namespace Core;

  class Request {
    protected $request;

   public function __construct()
   {
    $this->request = $_POST;
   }

    public function all() {
      return $_POST;
    }

    public function key($key){
      return $this->request[$key] ?? null;
    }

    public function validate($rules) {
      return Validator::validate($this->request, $rules);
    }
  }
?>