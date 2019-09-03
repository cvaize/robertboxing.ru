<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToFeedbackTableAndRequestsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('feedback', function(Blueprint $table) {
			$table->string('user_ip')->nullable();
			$table->string('user_agent')->nullable();
		});
		Schema::table('requests', function(Blueprint $table) {
			$table->string('user_ip')->nullable();
			$table->string('user_agent')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('feedback', function(Blueprint $table) {
			$table->dropColumn('user_ip');
			$table->dropColumn('user_agent');
		});
		Schema::table('requests', function(Blueprint $table) {
			$table->dropColumn('user_ip');
			$table->dropColumn('user_agent');
		});
	}
}
