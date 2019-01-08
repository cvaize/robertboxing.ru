<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToVkPosts extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('vk_posts', function (Blueprint $table) {
			$table->boolean('is_deleted')->nullable()->after('posted_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('vk_posts', function (Blueprint $table) {
			$table->dropColumn('is_deleted');
		});
	}
}
