<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailMaster extends Mailable
{
    use Queueable, SerializesModels;

    public $Email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email)
    {
        $this->Email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
	    if($this->Email->category == "user_email_auth")
	    {
	    	return $this->user_email_auth();
	    }
    }

    private function user_email_auth()
    {
	    return $this->from('joojang.help@gmail.com')
		    ->subject('이메일 인증을 해주세요.')
		    ->view('mail.email_auth')
//		    ->text('mails.email_auth_plain')
		    ->with(
			    [
				    'testValue' => 'test',
			    ]);
    }
}
