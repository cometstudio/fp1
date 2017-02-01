<?php

namespace App\Models;

use DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\SubmitSubscriptionEmail;

class Subscription extends Model
{
    use DispatchesJobs;

    protected $fillable = [
        'name',
        'text',
        'queued',
        'sent_at',
    ];
    protected $alreadyQueued = 0;

    public function beforeSave($attrubutes = [])
    {
        $this->alreadyQueued = $this->getOriginal('queued');
    }

    public function afterSave($attrubutes = [])
    {
        if(!empty($attrubutes['queued']) && !$this->alreadyQueued){
            // Mark other subscriptions as not queued
            DB::update("UPDATE ".$this->getTable()." SET queued = 0 WHERE id != ".$this->id);
            // Mark users as had not recieved this subscription
            DB::update("UPDATE ".(new User())->getTable()." SET subscription_sent = 0");
        }
    }

    public function submitSubscription()
    {
        // Get a subscription
        $subscription = $this->where('queued', '=', 1)
            ->orderBy('updated_at', 'DESC')
            ->first();

        if(!empty($subscription)){
            // Get a user to submit to
            $user = User::where('subscribed', '=', 1)
                ->where('subscription_sent', '=', 0)
                ->first();

            if(!empty($user)){
                $this->dispatch(new SubmitSubscriptionEmail($subscription, $user));
            // The subscription has been processed
            }else{
                $subscription->queued = 0;
                $subscription->sent_at = time();
                $subscription->update();
            }
        }
    }
}
