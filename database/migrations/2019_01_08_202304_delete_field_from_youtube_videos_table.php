<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteFieldFromYoutubeVideosTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('youtube_videos', function (Blueprint $table) {
			$table->dropColumn('is_deleted');
			$table->dropColumn('published_at');
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('youtube_videos', function (Blueprint $table) {
			$table->boolean('is_deleted')->nullable();
			$table->timestamp('published_at')->nullable();
			$table->dropSoftDeletes();
		});
	}
}
