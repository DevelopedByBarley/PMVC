<?php

namespace Core;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
  protected $mail = null;

  public function __construct()
  {
    $config = require base_path('config/mail.php');
    if ($config['usage'] === 'production') {
      $this->mail = new PHPMailer(true);

      $this->mail->SMTPOptions = array(
        'ssl' => array(
          'verify_peer' => false,
          'verify_peer_name' => false,
          'allow_self_signed' => true
        )
      );
      //$mail->SMTPDebug = 2;

      $this->mail->CharSet = 'UTF-8';
      $this->mail->IsSMTP();
      $this->mail->SMTPAuth = false;

      $this->mail->Host = $config['host'];

      // Setting the sender's email address
      $this->mail->setFrom($config['from']['address'], $config['from']['name']);

      // Adding the recipient's email address

      $this->mail->isHTML(true);
      $this->mail->WordWrap = 50;
    } else {
      $this->mail = new PHPMailer();
      $this->mail->isSMTP();
       $this->mail->SMTPDebug = 3;

      // SMTP beállítások alkalmazása
      $this->mail->Host = $config['host'];
      $this->mail->Port = $config['port'];
      $this->mail->Username = $config['username'];
      $this->mail->Password = $config['password'];
      $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
      $this->mail->SMTPAuth = true;
      $this->mail->CharSet = 'UTF-8';
      $this->mail->SMTPOptions = [
        'ssl' => [
          'verify_peer' => false,
          'verify_peer_name' => false,
          'allow_self_signed' => true
        ]
      ];

      // E-mail küldő cím és név beállítása
      $this->mail->setFrom($config['from']['address'], $config['from']['name']);
      $this->mail->isHTML(true);
    }
  }

  public function prepare($address, $subject)
  {
    try {
      $this->mail->clearAddresses();

      $this->mail->addAddress($address);

      $this->mail->Subject = $subject;

      return $this;
    } catch (Exception $e) {
      var_dump($e);
      return false;
    }
  }

  public function template($templateName, $data = [])
  {

    $templatePath = mail_temp_path($templateName);
    if (file_exists($templatePath)) {
      ob_start();
      extract($data);
      include $templatePath;
      $body = $this->mail->Body = ob_get_clean();
      $this->mail->AltBody = strip_tags($body);

      // Ellenőrizzük, hogy a Body nem üres
      if (empty($this->mail->Body)) {
        throw new Exception("A sablon tartalma üres.");
      }

      return $this;
    } else {
      throw new Exception("A(z) {$templateName} sablon nem található.");
    }
  }


  public function send()
  {
    if ($this->mail->send()) {
      Log::info('E-mail sikeresen elküldve: ' . implode(', ', array_column($this->mail->getToAddresses(), 0)), ['to' => $this->mail->getToAddresses()], 'mail');
      return true;
    } else {
      // Hibák kiíratása, ha a küldés nem sikerül
      $error = $this->mail->ErrorInfo;
      error_log($error); // Loggolás

      // Visszaadhatod a hibát, hogy könnyebben követhető legyen
      return $error;
    }
  }
}
