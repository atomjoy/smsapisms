# Smsapi Laravel sms notifications

Laravel SMS Notifications allows you to send SMS from your Laravel application (pl).

## Install

```sh
composer require "atomjoy/smsapisms"
```

## Config

config/smsapisms.php

```sh
php artisan vendor:publish --tag=smsapisms-config --force
```

## Routes

routes/web.php

```php
<?php

use App\Models\User;
use Atomjoy\Sms\Notifications\SendSms;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;

// Smsapi sms
Route::get('/sms', function () {
    try {
        $user = User::first();

        // Send smss
        $user->notify(
            new SendSms(
                'New Order [%idzdo:smsapi.pl/panel%]',
                ['48100100100', '44200200200']
            )
        );

        // Or with
        Notification::sendNow(
            $user,
            new SendSms(
                'New Order [%idzdo:smsapi.pl/panel%]',
                ['48100100100', '44200200200']
            )
        );
    } catch (\Exception $e) {
        return $e->getMessage();
    }

    return 'Message has been send.';
});
```

## Server

```sh
php artisan serve --host=localhost --port=8000
```
