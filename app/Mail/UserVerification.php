<?php

namespace App\Mail;

use App\Models\User;
use App\Models\VerificationToken;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use phpDocumentor\Reflection\Types\String_;

class UserVerification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = $this->user;
        $token = new VerificationToken(
            [
                'user_id' => $user->id,
                'token' => rand(100, 999) . rand(100,999),
                'expires_at' => now()->addHour(),
            ]
        );

        $token->save();

        $code = $token->token;

        $this->subject('Confirm your account');

        return $this->view('mail.mailVerification', compact('code'));
    }
}
