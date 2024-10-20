<?php


namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserLoggedIn;

class LoginNotification
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        // Ottieni l'utente che ha effettuato il login
        $user = $event->user;

        // Invia l'email di notifica
        Mail::to('riccardo.roscilli@gmail.com')->send(new UserLoggedIn($user));
    }
}
