<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NoticeUserMail extends Mailable
{
  use Queueable, SerializesModels;
  public $email;
  public $mobile;
  public $password;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct($email, $mobile, $password)
  {
    //
    $this->email = $email;
    $this->mobile = $mobile;
    $this->password = $password;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
//        return $this->view('view.name');
    return $this->markdown('emails.noticeUser')->with([
      'email' => $this->email,
      'mobile' => $this->mobile,
      'password' => $this->password
    ]);
  }
}
