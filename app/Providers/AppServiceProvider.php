<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use App\Models\Poll;
use App\Policies\PollPolicy;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Register policies (Laravel 11)
        Gate::policy(Poll::class, PollPolicy::class);

        // Fix setLocale error on some systems
        $locale = config('app.locale', 'id');
        $localeMap = [
            'id' => 'id_ID.UTF-8',
            'en' => 'en_US.UTF-8',
        ];
        $systemLocale = $localeMap[$locale] ?? 'en_US.UTF-8';
        if (!setlocale(LC_TIME, $systemLocale)) {
            setlocale(LC_TIME, 'C');
        }

        // Force HTTPS in production
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
