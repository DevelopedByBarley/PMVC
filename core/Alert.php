<?php

namespace Core;


class Alert
{
  protected $alert;

  public function __construct()
  {
    $this->alert = [
      'message' => '',
      'bg' => '',
      'color' => '',
    ];
  }

  public function danger($message = 'This is a alert!', $textColor = 'white')
  {
    $this->alert['message'] = $message;
    $this->alert['bg'] = 'danger';
    $this->alert['color'] = $textColor;

    Session::flash('alert', $this->alert);
    return $this;
  }

  public function redirect($uri)
  {
    Navigator::redirect($uri);
  }

  public function back() {
    Navigator::redirectBack();
  }
}

// Print the alert data
