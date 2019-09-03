<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteFieldsFromTables extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('sms', function (Blueprint $table) {
			$table->dropForeign(['requestsite_id']);
			$table->dropColumn('request_site_id');
		});

		Schema::table('emails', function (Blueprint $table) {
			$table->dropForeign(['requestsite_id']);
			$table->dropColumn('request_site_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('sms', function (Blueprint $table) {
			$table->unsignedInteger('request_site_id');
			$table->foreign('request_site_id')->references('id')->on('requests')->onUpdate('cascade')->onDelete('cascade');
		});

		Schema::table('emails', function (Blueprint $table) {
			$table->unsignedInteger('request_site_id');
			$table->foreign('request_site_id')->references('id')->on('requests')->onUpdate('cascade')->onDelete('cascade');
		});
	}
}
