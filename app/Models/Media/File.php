<?php

namespace App\Models\Media;

use App\Models\Users\User;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * App\Models\Media\File
 *
 * @property int                                                $id
 * @property int                                                $user_id
 * @property string|null                                        $fileable_type
 * @property string|null                                        $fileable_id
 * @property string|null                                        $public_path
 * @property string|null                                        $local_path
 * @property string|null                                        $name
 * @property string|null                                        $mime
 * @property int|null                                           $size
 * @property array|null                                         $payload
 * @property \Illuminate\Support\Carbon|null                    $created_at
 * @property \Illuminate\Support\Carbon|null                    $updated_at
 * @property string|null                                        $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $fileable
 * @property-read \App\Models\Users\User                        $user
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Media\File onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\File whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\File whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\File whereFileableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\File whereFileableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\File whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\File whereLocalPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\File whereMime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\File whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\File wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\File wherePublicPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\File whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\File whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\File whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Media\File withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Media\File withoutTrashed()
 * @mixin \Eloquent
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\File newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Media\File query()
 */
class File extends Model {
	use SoftDeletes;

	/**
	 * @var array
	 */
	protected $fillable = [
			'user_id',
			'fileable_id',
			'fileable_type',
			'public_path',
			'local_path',
			'name',
			'mime',
			'size',
			'payload',
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
			'payload' => 'array',
	];

	/**
	 * Default Filesystem Disk
	 *
	 * @var string
	 */
	private $defaultFilesystemDisk = 's3';

	/**
	 * @var FilesystemAdapter
	 */
	private $storage;

	/**
	 * @param $source
	 *
	 * @return File
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 * @throws \League\Flysystem\FileNotFoundException
	 */
	public static function add($source): File {
		$file = new self();
		$storage = $file->getStorage();
		$internalPath = $file->getInternalPath($file);
		$source = $file->getFileSource($source);

		$file->getStorage()->put($internalPath, $source);

		$file->{'user_id'} = auth()->check() ? auth()->user()->id : null;
		$file->{'mime'} = $storage->mimeType($internalPath);
		$file->{'size'} = $storage->getSize($internalPath);
		$file->{'public_path'} = $storage->url($internalPath);
		$file->{'local_path'} = $internalPath;
		$file->save();

		return $file;
	}


	/**
	 * @return FilesystemAdapter
	 */
	public function getStorage(): FilesystemAdapter {
		$storage = $this->storage;
		if ($storage === null) {
			$storage = Storage::disk($this->getDefaultFilesystemDisk());
		}

		return $storage;
	}

	/**
	 * @return string
	 */
	public function getDefaultFilesystemDisk(): string {
		return $this->defaultFilesystemDisk;
	}

	public function getInternalPath($file) {
		return 'public/articles/' . date('Y/m/d') . '/' . time();
	}

	/**
	 * @param $file
	 *
	 * @return bool|null|string
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function getFileSource($file): ?string {
		$type = $this->getFileType($file);
		switch ($type) {
			case 'uploadedFile':
				{
					/**
					 * @var UploadedFile $image
					 */
					$source = file_get_contents($file);
				}
				break;
			case 'url':
				{
					/**
					 * @var string $image
					 */
					$source = $this->getHttpContents($file);
				}
				break;
			default:
				{
					/**
					 * @var string $image
					 */
					$source = $file;
				}
				break;
		}

		return $source;
	}

	/**
	 * @param $image
	 *
	 * @return string
	 */
	public function getFileType($image): string {
		$type = 'blob';

		if (is_uploaded_file($image)) {
			$type = 'uploadedFile';
		} elseif (\is_string($image)
				&& filter_var($image, FILTER_VALIDATE_URL) !== false) {
			$type = 'url';
		}

		return $type;
	}

	/**
	 * @param string $url
	 *
	 * @return null|string
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function getHttpContents(string $url): ?string {

		$return = null;

		$client = new Client([
				'timeout' => 10,
		]);
		try {
			$response = $client->head($url);
			if ($response->getStatusCode() === 200) {
				$response = $client->request('GET', $url);
				$return = $response->getBody()->getContents();
			}
		} catch (\Exception $e) {
			Log::critical('file@getHttpContents failed', $e->toArray());
		}

		return $return;
	}

	public function user() {
		return $this->belongsTo(User::class);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\MorphTo
	 */
	public function fileable(): ?MorphTo {
		return $this->morphTo('fileable', 'fileable_type', 'fileable_id');
	}

	/**
	 * @param $file
	 *
	 * @return $this
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 * @throws \League\Flysystem\FileNotFoundException
	 */
	public function download($file): File {
		$storage = $this->getStorage();
		$internalPath = $this->getInternalPath($file);
		$source = $this->getFileSource($file);

		$this->getStorage()->put($internalPath, $source);

		$this->{'mime'} = $storage->mimeType($internalPath);
		$this->{'size'} = $storage->getSize($internalPath);
		$this->{'public_path'} = $storage->url($internalPath);
		$this->{'local_path'} = $internalPath;
		$this->save();

		return $this;
	}

	/**
	 * @return bool|null
	 */
	public function forceDelete(): ?bool {
		$this->getStorage()->delete($this->getLocalPath());

		return parent::forceDelete(); // TODO: Change the autogenerated stub
	}

	/**
	 * @return null|string
	 */
	public function getLocalPath(): ?string {
		return $this->{'local_path'};
	}

	/**
	 * @return null|string
	 */
	public function getPublicPath(): ?string {
		return $this->{'public_path'};
	}


}
