<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTypeOfFieldInFeedbackTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('feedback', function (Blueprint $table) {
			$table->text('user_ip')->change();
			$table->text('user_agent')->change();
		});
		Schema::table('requests', function (Blueprint $table) {
			$table->text('user_ip')->change();
			$table->text('user_agent')->change();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('feedback', function (Blueprint $table) {
			$table->string('user_ip')->change();
			$table->string('user_agent')->change();
		});
		Schema::table('requests', function (Blueprint $table) {
			$table->string('user_ip')->change();
			$table->string('user_agent')->change();
		});
	}
}
