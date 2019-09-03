<?php

namespace App\Models\Requests;

use App\Models\Users\Permission;
use App\Models\Users\User;
use function Couchbase\defaultEncoder;
use function foo\func;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Requests\Feedback
 *
 * @property int $id
 * @property string $text
 * @property int $user_id
 * @property int|null $status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Users\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Requests\Feedback filter($frd)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Requests\Feedback newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Requests\Feedback newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Requests\Feedback query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Requests\Feedback randomFeedback()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Requests\Feedback search($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Requests\Feedback whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Requests\Feedback whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Requests\Feedback whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Requests\Feedback whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Requests\Feedback whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Requests\Feedback whereUserId($value)
 * @mixin \Eloquent
 */
class Feedback extends Model {
	/**
	 * @var array
	 */
	protected $fillable = ['text', 'user_id', 'status_id', 'user_ip', 'user_agent'];

	/**
	 * @return string
	 */
	public function getShortText(): string {
		$text = $this->getText();
		if (strlen($text) > 150)
			$text = mb_substr($text, 0, 149) . '...';

		return $text;
	}

	/**
	 * @return string
	 */
	public function getText(): string {
		return $this->{'text'};
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
	public function statusClass(): string {
		$status = $this->getStatus();
		$class = '';

		if (1 === $status || null === $status) {
			$class = 'user__feedback-status--processing';
		} elseif (2 === $status) {
			$class = 'user__feedback-status--approved';
		} elseif (3 === $status) {
			$class = 'user__feedback-status--not-approved';
		}

		return $class;
	}

	/**
	 * @return int
	 */
	public function getStatus(): int {
		return $this->{'status_id'};
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user() {
		return $this->belongsTo(User::class);
	}

	/**
	 * @param Builder $query
	 * @return Builder
	 */
	public function scopeRandomFeedback(Builder $query): Builder {
		return $query->where('status_id', '=', 2)
			->inRandomOrder();
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
				->orWhere('text', 'like', '%' . $value . '%')
				->orWhere('status_id', '=', $value)
				->orWhereHas('user', function ($query) use ($value) {
					$query->where('f_name', 'like', '%' . $value . '%');
				});
		});
	}

	/**
	 * @param Builder $query
	 * @param array   $frd
	 * @return Builder
	 */
	public function scopeFilter(Builder $query, array $frd): Builder {
		foreach ($frd as $key => $value) {
			if (null === $value)
				continue;

			switch ($key) {
				case 'search':
					$query->search($value);
					break;
				case 'status_id': {
					$value = intval($value);
					if ($value !== 0) {
						$query->search($value);
					}
				} break;
				default:
					break;
			}
		}

		return $query;
	}

	/**
	 * @return bool
	 */
	public static function isCaptchaReviews(): bool {
		return env('CAPTCHA_REVIEWS');
	}
}
