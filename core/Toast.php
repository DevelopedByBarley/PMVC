<?php

namespace Core;


class Toast
{
  protected $toast;

  public function __construct()
  {
    $this->toast = [
      'message' => '',
      'type' => 'info',
      'bg' => 'primary',
      'color' => 'white',
      'time' => date('H:i'),
      'title' => null,
      'description' => null,
      'position' => 'top-0 end-0',
      'delay' => 5000,
      'icon_body' => null,
      'actions' => null
    ];
  }

  public function danger($message = 'This is a toast!')
  {
    $this->toast['message'] = $message;
    $this->toast['type'] = 'danger';
    $this->toast['bg'] = 'danger';
    $this->toast['color'] = 'white';

    Session::flash('toast', $this->toast);
    return $this;
  }

  public function success($message = 'This is a toast!')
  {
    $this->toast['message'] = $message;
    $this->toast['type'] = 'success';
    $this->toast['bg'] = 'success';
    $this->toast['color'] = 'white';

    Session::flash('toast', $this->toast);
    return $this;
  }

  public function warning($message = 'This is a toast!')
  {
    $this->toast['message'] = $message;
    $this->toast['type'] = 'warning';
    $this->toast['bg'] = 'warning';
    $this->toast['color'] = 'dark';

    Session::flash('toast', $this->toast);
    return $this;
  }

  public function info($message = 'This is a toast!')
  {
    $this->toast['message'] = $message;
    $this->toast['type'] = 'info';
    $this->toast['bg'] = 'info';
    $this->toast['color'] = 'white';

    Session::flash('toast', $this->toast);
    return $this;
  }

  /**
   * Set custom title for the toast
   */
  public function title($title)
  {
    $this->toast['title'] = $title;
    return $this;
  }

  /**
   * Set description for the toast
   */
  public function description($description)
  {
    $this->toast['description'] = $description;
    return $this;
  }

  /**
   * Set position of the toast
   * Examples: 'top-0 end-0', 'bottom-0 start-0', 'top-0 start-50 translate-middle-x'
   */
  public function position($position)
  {
    $this->toast['position'] = $position;
    return $this;
  }

  /**
   * Set delay in milliseconds
   */
  public function delay($delay)
  {
    $this->toast['delay'] = $delay;
    return $this;
  }

  /**
   * Set icon for the toast body
   */
  public function icon($icon)
  {
    $this->toast['icon_body'] = $icon;
    return $this;
  }

  /**
   * Add action buttons to the toast
   * Example: [['label' => 'OK', 'class' => 'btn-primary', 'onclick' => 'doSomething()']]
   */
  public function actions($actions)
  {
    $this->toast['actions'] = $actions;
    return $this;
  }

  /**
   * Show the toast (updates session)
   */
  public function show()
  {
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
