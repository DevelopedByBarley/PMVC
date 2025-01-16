<?php

namespace Core;


class Toast
{
  protected $toast;

  public function __construct()
  {
    $this->toast = [
      'message' => '',
      'bg' => '',
      'color' => '',
    ];
  }

  public function danger($message = 'This is a toast!', $textColor = 'white')
  {
    $this->toast['message'] = $message;
    $this->toast['bg'] = 'danger';
    $this->toast['color'] = $textColor;

    Session::flash('toast', $this->toast);
    return $this; // Enable chaining
  }

  public function redirect($uri)
  {
    Redirect::redirect($uri);
  }

  public function back() {
    Redirect::redirectBack();
  }
}
  