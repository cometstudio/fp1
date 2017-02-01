<?php

namespace App\Jobs;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;
use Illuminate\Contracts\Mail\Mailer;

class SubmitSubscriptionEmail implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $subscription;
    protected $user;

    /**
     * Create a new job instance.
     * @param $subscription
     * @param User $user
     */
    public function __construct(Subscription $subscription, User $user)
    {
        $this->subscription = $subscription;
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

        $settings = view()->shared('settings');

        $this->subscription->text = preg_replace("/\/images/i", env('APP_URL')."/images", $this->subscription->text);

        $mailer->send('emails.subscription', ['subscription'=>$this->subscription, 'settings' => $settings], function ($message) use ($settings) {
            $message->from('alex@fitnespraktika.ru', $settings->name);
            $message->to($this->user->email);
            $message->subject($this->subscription->name);
        });

        $this->user->subscription_sent = 1;
        $this->user->update();
    }
}
