<?php

namespace App\Mail;

use App\Models\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    public $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = Member::where([['email', $this->email], ['status_hapus', 0]])->first();

        $payload = array(
            'id'        => $user->id_peserta,
            'email'     => $user->email,
            'action'       => 'email-verification',
        );

        $crypt = Crypt::encrypt($payload);

        $url = config('app.url') . "/ls/click?click=$crypt";

        return $this
            ->subject('Register Notification')
            ->with([
                'url' => $url
            ])
            ->markdown('emails.verify');
    }
}
