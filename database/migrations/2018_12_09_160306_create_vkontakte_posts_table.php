<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVkontaktePostsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('vkontakte_posts', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('post_id');
			$table->text('post_text')->nullable();
			$table->timestamp('posted_at');
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
		Schema::dropIfExists('vkontakte_posts');
	}
}
