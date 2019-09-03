<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTrainingApiTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('training_api', function (Blueprint $table) {
			$table->renameColumn('at_day', 'day')->change();
			$table->string('start_at')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('training_api', function (Blueprint $table) {
			$table->renameColumn('day', 'at_day')->change();
			$table->dropColumn('start_at');
		});
	}
}
