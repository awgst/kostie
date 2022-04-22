<?php

namespace Modules\User\Listeners;

use Modules\User\Events\AskKostAvailability;

class ReduceCredit
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(AskKostAvailability $event)
    {
        $credit = ($event->user->credit-5) > 0 ? ($event->user->credit-5) : $event->user->credit;
        $event->user->credit = $credit;
        $event->user->save();
    }
}
