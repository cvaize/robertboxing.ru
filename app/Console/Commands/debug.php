<?php

namespace App\Console\Commands;


use Alaouy\Youtube\Facades\Youtube;
use App\Jobs\Media\Vk\GetVkPosts;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Storage;

class debug extends Command {
	use DispatchesJobs;
	/**
	 * @var string
	 * @since version
	 */
	protected $signature = 'debug';

	/**
	 * @var string
	 * @since version
	 */
	protected $description = 'Command description';

	/**
	 * @since debug constructor.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 *
	 */
	public function handle() {
		//$job = new GetVkPosts(true, true);
		//$this->dispatchNow($job);
	}
}