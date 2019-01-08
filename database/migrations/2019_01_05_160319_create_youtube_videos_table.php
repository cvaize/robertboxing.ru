<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYoutubeVideosTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('youtube_videos', function (Blueprint $table) {
			$table->increments('id');
			$table->string('video_id');
			$table->string('title');
			$table->string('channel_id');
			$table->timestamp('published_at');
			$table->boolean('is_deleted')->nullable();
			$table->text('payload')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('youtube_videos');
	}
}
