<?php

namespace App\Models\Requests;

use App\Models\Notifications\Email;
use App\Models\Notifications\Sms;
use App\Models\Users\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;

/**
 * App\Models\Requests\RequestSite
 *
 * @property int                                                                                                            $id
 * @property string                                                                                                         $name
 * @property string                                                                                                         $phone
 * @property \Illuminate\Support\Carbon|null                                                                                $created_at
 * @property \Illuminate\Support\Carbon|null                                                                                $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Requests\RequestSite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Requests\RequestSite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Requests\RequestSite query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Requests\RequestSite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Requests\RequestSite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Requests\RequestSite whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Requests\RequestSite wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Requests\RequestSite whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null                                                                                                       $user_id
 * @property int|null                                                                                                       $sms_id
 * @property int|null                                                                                                       $email_id
 * @property-read \App\Models\Notifications\Email|null                                                                      $email
 * @property-read \App\Models\Notifications\Sms|null                                                                        $sms
 * @property-read \App\Models\Users\User|null                                                                               $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Requests\RequestSite whereEmailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Requests\RequestSite whereSmsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Requests\RequestSite whereUserId($value)
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Requests\RequestSite filter($frd)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Requests\RequestSite search($value)
 */
class RequestSite extends Model {
	use Notifiable;

	/**
	 * @var string
	 */
	protected $table = 'requests';

	/**
	 * @var array
	 */
	protected $fillable = ['name', 'phone', 'sms_id', 'email_id', 'user_ip', 'user_agent'];

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->{'name'};
	}

	/**
	 * @return string
	 */
	public function getPhone(): string {
		return $this->{'phone'};
	}

	/**
	 * @return null|string
	 */
	public function getUserIp(): ?string {
		return $this->{'user_ip'};
	}

	/**
	 * @return null|string
	 */
	public function getUserAgent(): ?string {
		return $this->{'user_agent'};
	}

	/**
	 * @return string
	 */
	public function getDate(): string {
		/**
		 * @var Carbon $date
		 */
		//Carbon::setLocale('ru');
		//$date = $this->{'created_at'};
		//$postInfoDate = $date->day . '.' . $date->month . '.' . $date->year . ' ' . $date->hour . ':' . $date->;
		return $this->{'created_at'};
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user() {
		return $this->belongsTo(User::class);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function email() {
		return $this->belongsTo(Email::class);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function sms() {
		return $this->belongsTo(Sms::class);
	}

	/**
	 * @return bool
	 */
	public function isSmsed(): bool {
		return (null === $this->{'sms_id'}) ? false : true;
	}

	/**
	 * @return bool
	 */
	public function isEmailed(): bool {
		return (null === $this->{'email_id'}) ? false : true;
	}

	/**
	 * Route notifications for the mail channel.
	 *
	 * @param  \Illuminate\Notifications\Notification $notification
	 * @return string
	 */
	public function routeNotificationForMail($notification) {
		return $this->user()->get()[0]->getEmail();
	}

	/**
	 * @param Builder $query
	 * @param string  $value
	 * @return Builder
	 */
	public function scopeSearch(Builder $query, string $value): Builder {
		return $query->where(function ($query) use ($value) {
			/**
			 * @var Builder $query
			 */
			$query->orWhere('id', $value)
				->orWhere('name', 'like', '%' . $value . '%')
				->orWhere('phone', 'like', '%' . $value . '%');
		});
	}

	/**
	 * @param Builder $query
	 * @param array   $frd
	 * @return Builder
	 */
	public function scopeFilter(Builder $query, array $frd): Builder {
		foreach ($frd as $key => $value) {
			if (null === $value) {
				continue;
			}

			switch ($key) {
				case 'search':
					{
						$query->search($value);
					}
					break;
				default:
					break;
			}
		}


		return $query;
	}
}
