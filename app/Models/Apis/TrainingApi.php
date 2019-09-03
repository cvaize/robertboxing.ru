<?php

namespace App\Models\Apis;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Self_;

/**
 * App\Models\Apis\TrainingApi
 *
 * @property int                             $id
 * @property string|null                     $name
 * @property string|null                     $at_day
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Apis\TrainingApi filter($frd)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Apis\TrainingApi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Apis\TrainingApi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Apis\TrainingApi oneTraining($name)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Apis\TrainingApi query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Apis\TrainingApi whereAtDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Apis\TrainingApi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Apis\TrainingApi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Apis\TrainingApi whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Apis\TrainingApi whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TrainingApi extends Model {
	/**
	 * @var string
	 */
	protected $table = 'training_api';

	/**
	 * @var array
	 */
	protected $fillable = ['name', 'day', 'start_at'];

	/**
	 * @var string
	 */
	protected $cacheName = 'training.api';

	/**
	 * @return string
	 */
	public function nextTrainingTimeLeft(): string {
		$result = '';

		try {
			$nextTrainingId = $this->nextTrainingId();
			/**
			 * @var TrainingApi $nextTraining
			 */
			$nextTraining = TrainingApi::oneTraining($nextTrainingId)->get()[0];
			$atDay = $nextTraining->getDay();
			$startAt = $nextTraining->getStartAt();
			$nextDay = $this->englishDay($atDay);
			$time = intval(explode(':', $startAt)[0]);
			$numericalNextDay = $this->numericalDay($nextDay);

			if (Carbon::now()->dayOfWeek !== $numericalNextDay) {
				$nextTrainingTime = Carbon::now()->next($numericalNextDay)->addHours($time);
				$timeLeft = Carbon::now()->diff($nextTrainingTime);
			} else {
				$nextTrainingTime = Carbon::today()->addHours($time);
				$timeLeft = Carbon::now()->diff($nextTrainingTime);
			}

			//$dd = ($timeLeft->days < 10) ? '0' . $timeLeft->days : $timeLeft->days;
			//$hh = ($timeLeft->h < 10) ? '0' . $timeLeft->h : $timeLeft->h;
			//$mm = ($timeLeft->i < 10) ? '0' . $timeLeft->i : $timeLeft->i;
			$hh = $timeLeft->h;
			$hh += ($timeLeft->d !== 0) ? $timeLeft->d * 24 : 0;
			$mm = $timeLeft->i;
			$ss = $timeLeft->s;

			//$result = $dd . 'Д:' . $hh . ':' .  $mm;
			$result = 'hours-' . $hh . ' minutes-' . $mm . ' seconds-' . $ss;
		} catch (\Exception $exception) {
			Log::critical('method TrainingApi@nextTrainingTimeLeft failed', [
				'message' => $exception->getMessage(),
				'line'    => $exception->getLine(),
				'code'    => $exception->getCode()
			]);
		}

		return $result;
	}

	/**
	 * @return string
	 */
	public function nextTraining(): string {
		$training = $this->daysWhichHasTime()->get();
		$minDifference = null;
		$nextTraining = '';
		$nextTrainingId = 0;
		$cacheName = $this->getCacheName();

		//Cache::delete($cacheName);

		if (!$this->isCached($cacheName)) {
			//dd($training);
			foreach ($training as $item) {
				/**
				 * @var TrainingApi $item
				 */
				$now = Carbon::now();
				$atDay = $item->getDay();
				$startAt = $item->getStartAt();
				$day = $this->englishDay($atDay);
				$time = intval(explode(':', $startAt)[0]);
				$numericalDay = $this->numericalDay($day);
				$nextDate = Carbon::now()->next($numericalDay);


				if (Carbon::now()->dayOfWeek === $numericalDay) {
					$nextTrainingTime = Carbon::today()->addHours($time);
					$difference = Carbon::now()->diff($nextTrainingTime);
					$inverse = $difference->invert;
					$timeLeft = $difference->i;

					if ($timeLeft > 1 && !$inverse) {
						$nextTraining = $item->getName();
						$nextTrainingId = $item->getKey();
						break;
					}
				}

				if (null === $minDifference) {
					$minDifference = $now->diffInHours($nextDate);
					$nextTraining = $item->getName();
					$nextTrainingId = $item->getKey();
					continue;
				}

				$difference = $now->diffInHours($nextDate);

				if ($difference < $minDifference) {
					$minDifference = $difference;
					$nextTraining = $item->getName();
					$nextTrainingId = $item->getKey();
				}
			}
			$this->setCache($cacheName, $nextTraining . '_' . $nextTrainingId);
		} else {
			$nextTraining = $this->getCache($cacheName);
			$nextTraining = explode('_', $nextTraining)[0];
		}

		return $nextTraining;
	}

	/**
	 * @return string
	 */
	private function nextTrainingId(): string {
		$cacheName = $this->getCacheName();
		$nextTraining = $this->getCache($cacheName);

		return explode('_', $nextTraining)[1];
	}

	/**
	 * @return string
	 */
	private function getCacheName(): string {
		return $this->cacheName;
	}

	/**
	 * @param string $cacheName
	 * @return bool
	 */
	private function isCached(string $cacheName): bool {
		$data = Cache::get($cacheName);

		return (null === $data) ? false : true;
	}

	/**
	 * @return string
	 */
	public function getDay(): string {
		return $this->{'day'};
	}

	/**
	 * @return null|string
	 */
	public function getStartAt(): ?string {
		return $this->{'start_at'};
	}

	/**
	 * @param string $russianDay
	 * @return null|string
	 */
	private function englishDay(string $russianDay): ?string {
		$result = null;

		switch ($russianDay) {
			case 'Пн':
				{
					$result = 'Monday';
				}
				break;
			case 'Вт':
				{
					$result = 'Tuesday';
				}
				break;
			case 'Ср':
				{
					$result = 'Wednesday';
				}
				break;
			case 'Чт':
				{
					$result = 'Thursday';
				}
				break;
			case 'Пт':
				{
					$result = 'Friday';
				}
				break;
			case 'Сб':
				{
					$result = 'Saturday';
				}
				break;
			case 'Вс':
				{
					$result = 'Sunday';
				}
				break;
			default:
				break;
		}

		return $result;
	}

	/**
	 * @param string $day
	 * @return int
	 */
	private function numericalDay(string $day): int {
		$days = [
			'Monday'    => Carbon::MONDAY,
			'Tuesday'   => Carbon::TUESDAY,
			'Wednesday' => Carbon::WEDNESDAY,
			'Thursday'  => Carbon::THURSDAY,
			'Friday'    => Carbon::FRIDAY,
			'Saturday'  => Carbon::SATURDAY,
			'Sunday'    => Carbon::SUNDAY
		];

		return $days[$day];
	}

	/**
	 * @return string
	 */
	public function getName(): ?string {
		return $this->{'name'};
	}

	/**
	 * @param string $value
	 */
	private function setCache(string $cacheName, string $value) {
		Cache::put($cacheName, $value, Carbon::now()->addMinutes(5));
	}

	/**
	 * @param string $cacheName
	 * @return mixed
	 */
	private function getCache(string $cacheName) {
		return Cache::get($cacheName);
	}

	/**
	 * @param Builder $query
	 * @param string  $name
	 * @return Builder
	 */
	public function scopeOneTraining(Builder $query, string $value): Builder {
		return $query->orWhere('name', 'like', '%' . $value . '%')->orWhere('id', $value);
	}

	/**
	 * @param Builder $query
	 * @return Builder
	 */
	public function scopeDaysWhichHasTime(Builder $query): Builder {
		return $query->where('start_at', '!=', null);
	}

	/**
	 * @param Builder    $query
	 * @param array|null $frd
	 * @return Builder
	 */
	public function scopeFilter(Builder $query, ?array $frd): Builder {
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
			}
		}

		return $query;
	}
}
