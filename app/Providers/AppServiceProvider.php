<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{



public function boot(): void
{
    // Customize the verification email notification
    VerifyEmail::toMailUsing(function ($notifiable, $url) {
        return (new MailMessage)
            ->subject('Verify Your Email Address')  // Set the subject of the email
            ->line('Please click the button below to verify your email address.')  // Add a message
            ->action('Verify Email Address', $url);  // The verification link
    });
}

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
}
