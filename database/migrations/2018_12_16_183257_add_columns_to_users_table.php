<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToUsersTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('users', function (Blueprint $table) {
			$table->string('phone')->after('email')->nullable();
			$table->string('telegram')->after('phone')->nullable();
			$table->timestamp('email_verified_at')->after('telegram')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('users', function (Blueprint $table) {
			$table->dropColumn('phone');
			$table->dropColumn('telegram');
			$table->dropColumn('email_verified_at');
		});
	}
}
