<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendUserPassword extends Mailable
{
    use Queueable, SerializesModels;

    protected $label;

    protected $password;

    /**
     * Create a new message instance.
     * @return void
     */
    public function __construct($label, $password)
    {
        $this->label = $label;

        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('【通知】')->view('emails.password')->with([
            'label' => $this->label,
            'password' => $this->password,
        ]);
    }
}
