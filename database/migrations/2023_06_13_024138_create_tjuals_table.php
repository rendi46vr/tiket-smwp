<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTjualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tjuals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('NP')->unique();
            $table->string('wa')->nullable();
            $table->string('email')->nullable();
            $table->date('tgl')->nullable();
            $table->date('tgljual');
            $table->string('qty');
            $table->double('totalbayar', 20.2);
            $table->string('token')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('tjuals');
    }
}
