<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToRequestsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('requests', function (Blueprint $table) {
			$table->unsignedInteger('sms_id')->nullable()->after('phone');
			$table->foreign('sms_id')->references('id')->on('sms')->onUpdate('cascade')->onDelete('cascade');

			$table->unsignedInteger('email_id')->nullable()->after('sms_id');
			$table->foreign('email_id')->references('id')->on('emails')->onUpdate('cascade')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('requests', function (Blueprint $table) {
			$table->dropForeign(['sms_id', 'email_id']);
			$table->dropColumn('sms_id');
			$table->dropColumn('email_id');
		});
	}
}