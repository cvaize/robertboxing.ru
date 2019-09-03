<?php

namespace App\Notifications;

use App\Models\Notifications\Email;
use App\Models\Notifications\Sms;
use App\Models\Requests\RequestSite;
use App\Models\Users\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;
use NotificationChannels\SmscRu\SmscRuChannel;
use NotificationChannels\SmscRu\SmscRuMessage;

class RequestLeft extends Notification {
	use Queueable;

	/**
	 * @var RequestSite
	 */
	protected $request;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct(RequestSite $requestSite) {
		$this->request = $requestSite;
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @param  mixed $notifiable
	 * @return array
	 */
	public function via($notifiable) {
		if ($this->isSmsNotificationEnabled($notifiable) && $this->isEmailNotificationEnabled($notifiable)) {
			$channels = ['mail', SmscRuChannel::class];
		} elseif ($this->isSmsNotificationEnabled($notifiable) && !$this->isEmailNotificationEnabled($notifiable)) {
			$channels = [SmscRuChannel::class];
		} elseif (!$this->isSmsNotificationEnabled($notifiable) && $this->isEmailNotificationEnabled($notifiable)) {
			$channels = ['mail'];
		} else {
			$channels = [];
		}

		return $channels;
	}

	/**
	 * Get the mail representation of the notification.
	 *
	 * @param $notifiable
	 * @return bool|MailMessage
	 */
	public function toMail($notifiable) {
		/**
		 * @var Email $email
		 */
		$email = $this->request->email()->get()[0];

		return (new MailMessage)
			->subject('Запись клиента')
			->line($email->getText());
	}

	/**
	 * @param $notifiable
	 * @return mixed
	 */
	public function toSmscRu($notifiable) {
		/**
		 * @var Sms $sms
		 */
		$sms = $this->request->sms()->get()[0];
		$smsText = $sms->getText();

		if (strlen($smsText) > 64) {
			$result = mb_substr($smsText, 0, 63);
		} else {
			$result = $smsText;
		}

		$message = SmscRuMessage::create($result);

		return $message;
	}

	/**
	 * @param User $notifiable
	 * @return bool
	 */
	private function isEmailNotificationEnabled(User $notifiable): bool {
		return $notifiable->isEmailable();
	}

	/**
	 * @param User $notifiable
	 * @return bool
	 */
	private function isSmsNotificationEnabled(User $notifiable): bool {
		return $notifiable->isSmsable();
	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @param  mixed $notifiable
	 * @return array
	 */
	public function toArray($notifiable) {
		return [
			//
		];
	}
}
