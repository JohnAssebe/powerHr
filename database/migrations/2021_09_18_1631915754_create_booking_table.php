<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingTable extends Migration
{
	public function up()
	{
		Schema::create('booking', function (Blueprint $table) {
			$table->id('id');
			$table->string('booking_id');
			$table->integer('organization_id');
			$table->integer('user_id');
			$table->integer('emp_id');
			$table->date('date');
			$table->string('start_time');
			$table->string('end_time');
			$table->enum('session_type', ["coaching", "mentoring", "therapy_session"]);
			$table->string('booking_status');
			$table->text('request')->nullable();
			$table->string('type')->nullable()->default('individual');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::dropIfExists('booking');
	}
}
