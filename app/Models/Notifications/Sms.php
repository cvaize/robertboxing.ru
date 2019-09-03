<?php

namespace App\Models\Notifications;

use App\Models\Requests\RequestSite;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Notifications\Sms
 *
 * @property int $id
 * @property string $text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\Sms newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\Sms newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\Sms query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\Sms whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\Sms whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\Sms whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\Sms whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Sms extends Model {
	/**
	 * @var string
	 */
	protected $table = 'sms';

	/**
	 * @var array
	 */
	protected $fillable = ['text'];

	/**
	 * @return string
	 */
	public function getText(): string {
		return $this->{'text'};
	}
}
