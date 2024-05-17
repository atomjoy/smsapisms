# Smsapisms Laravel sms notifications

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
use Atomjoy\Sms\Notifications\OrderSms;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;

// Smsapi sms
Route::get('/sms', function () {
    try {
        $user = User::first();

        // Send smss
        $user->notify(
            new OrderSms(
                'New Order [%idzdo:smsapi.pl/panel%]',
                ['48100100100', '44200200200']
            )
        );

        // Or with
        Notification::sendNow(
            $user,
            new OrderSms(
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

## Run server

```sh
php artisan serve --host=localhost --port=8000
```
