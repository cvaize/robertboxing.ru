<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFieldsName extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('sms', function (Blueprint $table) {
			$table->renameColumn('requestsite_id', 'request_site_id')->change();
		});

		Schema::table('emails', function (Blueprint $table) {
			$table->renameColumn('requestsite_id', 'request_site_id')->change();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('sms', function (Blueprint $table) {
			$table->renameColumn('request_site_id', 'requestsite_id')->change();
		});

		Schema::table('emails', function (Blueprint $table) {
			$table->renameColumn('request_site_id', 'requestsite_id')->change();
		});
	}
}
