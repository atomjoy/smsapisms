<?php

use App\Models\User;
use Atomjoy\Sms\Notifications\SendSms;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;

// Smsapi sms
Route::get('/smsapisms', function () {
	try {
		$user = User::first();
		$user->notify(
			new SendSms(
				'New Order [%idzdo:smsapi.pl/panel%]',
				['48100100100']
			)
		);

		Notification::sendNow(
			$user,
			new SendSms(
				'New Order [%idzdo:smsapi.pl/panel%]',
				['48100100100']
			)
		);
	} catch (\Exception $e) {
		// Resend from another channel if error
		return $e->getMessage();
	}

	return 'Message has been send.';
});
