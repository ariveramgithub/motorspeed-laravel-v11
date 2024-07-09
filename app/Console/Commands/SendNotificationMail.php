<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReminderMail;
use App\Models\Reminder;
use App\Models\User;

class SendNotificationMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-notification-mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía notificación de evento próximo vía email';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $user = User::find(1);

        $reminders = Reminder::whereDate('start', date('Y-m-d', time()))
            ->where('sent', false)
            ->get();

        if( count($reminders) > 0 ){
            Mail::to($user->email)->send(new ReminderMail( $reminders ));
            Reminder::whereDate('start', date('Y-m-d', time()))
            ->where('sent', false)
            ->update([
                'sent' => true,
            ]);
        }
        
    }
}
