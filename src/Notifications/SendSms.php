<?php

namespace Atomjoy\Sms\Notifications;

use Atomjoy\Sms\Channels\SmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Smsapi\Client\Feature\Sms\Bag\SendSmsBag;
use Smsapi\Client\Feature\Sms\Bag\SendSmssBag;

class SendSms extends Notification
{
	use Queueable;

	public function __construct(
		public string $message = '',
		public array $mobile = [],
	) {
		$this->afterCommit();
	}

	/**
	 * Get the notification's database type.
	 *
	 * @return string
	 */
	public function databaseType(object $notifiable): string
	{
		return 'sms-channel';
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @return array<int, string>
	 */
	public function via($notifiable)
	{
		return [SmsChannel::class, 'database'];
	}

	/**
	 * Get the sms message representation of the notification.
	 *
	 * @return App\Channels\Sms\SmsMessage
	 */
	public function toSms($notifiable): SendSmsBag|SendSmssBag
	{
		return SendSmssBag::withMessage($this->mobile, $this->message);
	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray($notifiable)
	{
		return [
			'id' => $notifiable->id,
			'mobile' => $this->mobile,
			'message' => $this->message,
		];
	}
}
