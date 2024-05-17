<?php

namespace Atomjoy\Sms\Channels;

use Atomjoy\Sms\Events\SmsSent;
use Atomjoy\Sms\Events\SmsSentError;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Smsapi\Client\Curl\SmsapiHttpClient;
use Smsapi\Client\Feature\Sms\Bag\SendSmsBag;
use Smsapi\Client\Feature\Sms\Bag\SendSmssBag;

/**
 * SmsChannel class
 *
 * Install:
 * composer require smsapi/php-client
 */
class SmsChannel
{
	/**
	 * Undocumented function
	 *
	 * @param \App\Models\User $notifiable
	 * @param \Illuminate\Notifications\Notification $notification
	 * @return mixed True on success or void on error
	 */
	public function send($notifiable, Notification $notification)
	{
		$sms = method_exists($notification, 'toSms')
			? $notification->toSms($notifiable)
			: '';

		if (
			!$sms instanceof SendSmsBag &&
			!$sms instanceof SendSmssBag
		) {
			return;
		}

		$sms->from = config('smsapisms.api_from', 'Test');
		$sms->encoding = config('smsapisms.api_encoding', 'utf-8');
		$sms->details = config('smsapisms.api_details', true);
		if (config('smsapisms.api_test', false)) {
			$sms->test = 1;
		}

		try {
			if (
				config('smsapisms.api_service') == 'se' ||
				config('smsapisms.api_service') == 'bg'
			) {
				$res = (new SmsapiHttpClient())
					->smsapiComServiceWithUri(config('smsapisms.api_token', 'EMPTY_API_TOKEN'), 'https://smsapi.io/')
					->smsFeature()->sendSmss($sms);
			} else if (config('smsapisms.api_service') == 'com') {
				$res = (new SmsapiHttpClient())
					->smsapiComService(config('smsapisms.api_token', 'EMPTY_API_TOKEN'))
					->smsFeature()->sendSmss($sms);
			} else {
				$res = (new SmsapiHttpClient())
					->smsapiPlService(config('smsapisms.api_token', 'EMPTY_API_TOKEN'))
					->smsFeature()->sendSmss($sms);
			}

			$this->log($res, 'SmsSent');
			SmsSent::dispatch($sms, $res);
			return $res;
		} catch (\Exception $e) {
			report($e);
			$this->log($sms, 'SmsError');
			SmsSentError::dispatch($sms, $e);
		}
	}

	/**
	 * Log to file
	 *
	 * @param mixed $data
	 * @param string $msg
	 * @return void
	 */
	function log($data, $msg = 'SmsSent'): void
	{
		Log::build([
			'driver' => 'single',
			'path' => storage_path('logs/smsapisms.log'),
		])->info($msg, array('sms' => $data));
	}
}
