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
      'time' => 'most'
    ];
  }

  public function danger($message = 'This is a toast!')
  {
    $this->toast['message'] = $message;
    $this->toast['color'] = 'white';
    $this->toast['bg'] = 'danger';

    Session::flash('toast', $this->toast);
    return $this;
  }
  public function success($message = 'This is a toast!')
  {
    $this->toast['message'] = $message;
    $this->toast['color'] = 'white';
    $this->toast['bg'] = 'success';

    Session::flash('toast', $this->toast);
    return $this;
  }
  public function warning($message = 'This is a toast!')
  {
    $this->toast['message'] = $message;
    $this->toast['color'] = 'white';
    $this->toast['bg'] = 'warning';

    Session::flash('toast', $this->toast);
    return $this;
  }
  public function info($message = 'This is a toast!')
  {
    $this->toast['message'] = $message;
    $this->toast['color'] = 'white';
    $this->toast['bg'] = 'info';

    Session::flash('toast', $this->toast);
    return $this;
  }

  public function redirect($uri)
  {
    Navigator::redirect($uri);
  }

  public function back()
  {
    Navigator::redirectBack();
  }
}

// Print the toast data
