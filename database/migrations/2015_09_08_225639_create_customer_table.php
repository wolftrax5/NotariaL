<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('customer', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('name');
			$table->string('fathers_last_name');
			$table->string('mothers_last_name');
			$table->string('rfc');
			$table->string('phone');
			$table->date('birthdate');
			$table->enum('marital_status',['soltero/a','casado/a','divorciado/a','viudo/a']);
			$table->string('occupation');
			$table->string('from');

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
		Schema::drop('customer');
	}

}
