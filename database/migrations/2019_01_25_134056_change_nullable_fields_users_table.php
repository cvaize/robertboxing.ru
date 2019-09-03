<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeNullableFieldsUsersTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('users', function(Blueprint $table) {
			$table->string('email')->nullable()->change();
			$table->string('phone')->nullable(false)->change();
			$table->string('f_name')->nullable(false)->change();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('users', function(Blueprint $table) {
			$table->string('email')->nullable(false)->change();
			$table->string('phone')->nullable()->change();
			$table->string('f_name')->nullable()->change();
		});
	}
}
