<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteFieldFromVkPostsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('vk_posts', function (Blueprint $table) {
			$table->dropColumn('is_deleted');
			$table->dropColumn('posted_at');
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('vk_posts', function (Blueprint $table) {
			$table->boolean('is_deleted')->nullable();
			$table->timestamp('posted_at')->nullable();
			$table->dropSoftDeletes();
		});
	}
}
