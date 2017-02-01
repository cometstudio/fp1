<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;
use Illuminate\Contracts\Mail\Mailer;

class SubmitVerificationEmail implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     * SendVerificationEmail constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     * @param Mailer $mailer
     * @throws \Exception
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        if(empty($this->user->email)) throw new \Exception;
        if(empty($this->user->token)) throw new \Exception;

        $settings = view()->shared('settings');

        $verificationURL = url()->route('verify', ['token'=>$this->user->token]);

        $mailer->send('emails.verify', ['verificationURL' => $verificationURL, 'settings' => $settings], function ($message) use ($settings) {
            $message->from('alex@fitnespraktika.ru', $settings->name);
            $message->to($this->user->email);
            $message->subject('Подтвердите свой e-mail');
        });
    }
}
