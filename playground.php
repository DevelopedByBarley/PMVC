<?php
class Toast
{
  protected $toast = [
    'bg' => null
  ];


  public function danger()
  {
    $this->toast = [
      'bg' => 'danger'
    ];

    return $this;
  }

  public function set($key)
  {
    $_SESSION['_flash'][$key] = $this->toast;
    return $this;
  }

  public function redirect($newURL)
  {
    header('Location: ' . $newURL);
  }
}

class Alert
{
  protected $alert = [
    'bg' => null
  ];


  public function danger()
  {
    $this->alert = [
      'bg' => 'danger'
    ];

    return $this;
  }

  public function set($key)
  {
    $_SESSION['_flash'][$key] = $this->alert;
    return $this;
  }

  public function redirect($newURL)
  {
    header('Location: ' . $newURL);
  }
}

class Notification
{
  const MAP = [
    'toast' => Toast::class,
    'alert' => Alert::class
  ];


  public function resolver($key)
  {
    return new (static::MAP[$key]);
  }
}

$notification = new Notification();

$notification->resolver('alert')->danger()->set('')->redirect('/asdad');
