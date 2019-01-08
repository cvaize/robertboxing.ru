<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstagramPostsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('instagram_posts', function (Blueprint $table) {
			$table->increments('id');
			$table->string('post_id');
			$table->string('link');
			$table->timestamp('posted_at');
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
		Schema::dropIfExists('instagram_posts');
	}
}
