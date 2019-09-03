<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEmail extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('emails', function (Blueprint $table) {
			$table->increments('id');
			$table->string('text');

			$table->unsignedInteger('requestsite_id')->nullable();
			$table->foreign('requestsite_id')->references('id')->on('requests')->onUpdate('cascade')->onDelete('cascade');

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::disableForeignKeyConstraints();
		Schema::dropIfExists('emails');
		Schema::enableForeignKeyConstraints();
	}
}
