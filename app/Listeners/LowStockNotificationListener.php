<?php

namespace App\Listeners;

use App\Events\LowStockEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\LowStockNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class LowStockNotificationListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LowStockEvent $event): void
    {
        // In case of low stock notification not sent before
        $totalQty = $event->stock->initial_stock;   // 200
        $currentQty = $event->stock->current_stock; // 90
        // 90 < 100
        if ( !$event->stock->notified && $currentQty <= ($totalQty / 2)) {
            Log::alert("Low Stock Notification", ['id' => $event->stock->id]);
            // Send Notification
            Notification::route('mail', config('services.merchant_mail'))->notify(
                new LowStockNotification(
                    $event->stock->ingredient()->first()->name,
                    $currentQty
                )
                );
            
            // Update the notified to true
            $event->stock->notified = true;
            $event->stock->save();

            // whenever we added a stock we will update it again to false
        }
    }
}
