<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToTrainingApiTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('training_api', function (Blueprint $table) {
			$table->integer('ordering')->nullable()->after('start_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('training_api', function (Blueprint $table) {
			$table->dropColumn('ordering');
		});
	}
}
