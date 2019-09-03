<?php

namespace App\Models\Notifications;

use App\Models\Requests\RequestSite;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\Notifications\Email
 *
 * @property int $id
 * @property string $text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\Email newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\Email newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\Email query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\Email whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\Email whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\Email whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications\Email whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Email extends Model {
	/**
	 * @var string
	 */
	protected $table = 'emails';

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
