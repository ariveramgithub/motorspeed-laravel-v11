<?php

namespace App\Observers;

use App\Models\Event;
use App\Models\Reminder;

class EventObserver
{
    /**
     * Handle the Event "created" event.
     */
    public function created(Event $event): void
    {
        $aDayAgo = date_format(date_add(date_create(date("Y-m-d", strtotime($event->event_start))), date_interval_create_from_date_string("-1 days")),"Y-m-d");
        $twoDaysAgo = date_format(date_add(date_create(date("Y-m-d", strtotime($event->event_start))), date_interval_create_from_date_string("-2 days")),"Y-m-d");
        // Create reminder row
        // Si Variable contiene fecha/hora valido y es mayor o igual a la fecha/hora actual
        if( date_create($event->event_start) && strtotime($aDayAgo) >= time() ) {
            // Recordatorio 24 horas antes
            Reminder::create([
                "relationship" => "events",
                "fk" => $event->id,
                "start" => $aDayAgo,
            ]);

            if( strtotime($twoDaysAgo) >= time() ) {
                // Recordatorio 48 horas antes
                Reminder::create([
                    "relationship" => "events",
                    "fk" => $event->id,
                    "start" => $twoDaysAgo,
                ]);
            }
        }
    }

    /**
     * Handle the Event "updated" event.
     */
    public function updated(Event $event): void
    {
        Reminder::where('relationship', 'events')->where('fk', $event->id)->delete();

        $aDayAgo = date_format(date_add(date_create(date("Y-m-d", strtotime($event->event_start))), date_interval_create_from_date_string("-1 days")),"Y-m-d");
        $twoDaysAgo = date_format(date_add(date_create(date("Y-m-d", strtotime($event->event_start))), date_interval_create_from_date_string("-2 days")),"Y-m-d");

        // Si Variable contiene fecha/hora valido y es mayor o igual a la fecha/hora actual
        if( date_create($event->event_start) && strtotime($aDayAgo) >= time() ) {
            // Recordatorio 24 horas antes
            Reminder::create([
                "relationship" => "events",
                "fk" => $event->id,
                "start" => $aDayAgo,
            ]);

            if( strtotime($twoDaysAgo) >= time() ) {
                // Recordatorio 48 horas antes
                Reminder::create([
                    "relationship" => "events",
                    "fk" => $event->id,
                    "start" => $twoDaysAgo,
                ]);
            }
        }
    }

    /**
     * Handle the Event "deleted" event.
     */
    public function deleted(Event $event): void
    {
        Reminder::where('relationship', 'events')->where('fk', $event->id)->delete();
    }

    /**
     * Handle the Event "restored" event.
     */
    public function restored(Event $event): void
    {
        //
    }

    /**
     * Handle the Event "force deleted" event.
     */
    public function forceDeleted(Event $event): void
    {
        Reminder::where('relationship', 'events')->where('fk', $event->id)->delete();
    }
}
