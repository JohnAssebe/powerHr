<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeTable extends Migration
{
	public function up()
	{
		Schema::create('employee', function (Blueprint $table) {
			$table->id('emp_id');
			$table->integer('organization_id')->default('1');
			$table->unsignedBigInteger('user_id');
			// $table->string('full_name');
			$table->string('sun', 150)->nullable()->default('NULL');
			$table->string('mon', 150)->nullable()->default('NULL');
			$table->string('tue', 150)->nullable()->default('NULL');
			$table->string('wed', 150)->nullable()->default('NULL');
			$table->string('thu', 150)->nullable()->default('NULL');
			$table->string('fri', 150)->nullable()->default('NULL');
			$table->string('sat', 150)->nullable()->default('NULL');
			$table->tinyInteger('status')->default('1');
			$table->string('profession');
			$table->tinyInteger('isdelete')->default('0');
			
			$table->timestamps();
			$table->foreign('user_id')->references('id')->on('users');
		});
	}

	public function down()
	{
		Schema::dropIfExists('employee');
	}
}
