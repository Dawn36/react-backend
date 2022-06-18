<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer("genre_id");
            $table->integer("number_in_stock");
            $table->float("daily_rental_rate");
            $table->enum('liked', ['true', 'false'])->default("false");
            $table->softDeletes();
            $table->dateTime("publish_date");
            $table->dateTime("updated_by")->nullable();
            $table->timestamps();
        });
        Schema::create('genres', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->softDeletes();
            $table->dateTime("updated_by")->nullable();
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
        Schema::dropIfExists('movies');
        Schema::dropIfExists('genres');
    }
}
