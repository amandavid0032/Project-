<?php
defined('BASEPATH') or exit('No direct script access allowed');

class EmailLibrary
{
  protected $ci;

  public function __construct()
  {
    $this->ci = &get_instance();
  }

  public function mailConfiguration(): void
  {
    $config = array(
      'protocol' => 'smtp',
      'smtp_host' => 'mail.wavelinx.in',
      'smtp_port' => 465,
      'smtp_user' => 'demo@wavelinx.in', // change it to yours
      'smtp_pass' => '123456', // change it to yours
      'mailtype' => 'html',
      'charset' => 'iso-8859-1',
      'wordwrap' => TRUE
    );
    $this->ci->load->library('email', $config);
  }

  public function sendSingleMail(
    string $subject = '',
    string $userName = '',
    string $from = '',
    string $to = '',
    string $message = '',
    string $attachment = '',
    string $mailType = 'html'
  ): array {
    try {
      $this->mailConfiguration();
      $this->ci->email->set_newline("\r\n");
      $this->ci->email->subject($subject);
      $this->ci->email->from($from, $userName);
      $this->ci->email->to($to);
      $this->ci->email->message($message);

      if ($mailType == 'html') {
        $this->ci->email->set_mailtype("html");
      }

      if ($attachment !== '') {
        $this->ci->email->attach($attachment);
      }

      if (!$this->ci->email->send()) {

        // removing of portion from the string  
        $errorMessage  = substr($this->ci->email->print_debugger(), 0, strpos($this->ci->email->print_debugger(), "Date"));
        throw new Exception($errorMessage);
      }
      $response = [
        'status' => SUCCESS,
        "to" => $to,
        "from" => "$userName <$from>",
        'response' => 'sent'
      ];
    } catch (Exception $e) {
      $response = [
        'status' => FAILED,
        "to" => $to,
        "from" => "$userName <$from>",
        'response' => $e->getMessage()
      ];
    }
    return $response;
  }
}
