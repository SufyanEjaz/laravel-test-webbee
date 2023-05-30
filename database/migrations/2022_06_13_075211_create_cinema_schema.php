<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCinemaSchema extends Migration
{
    /** ToDo: Create a migration that creates all tables for the following user stories

    For an example on how a UI for an api using this might look like, please try to book a show at https://in.bookmyshow.com/.
    To not introduce additional complexity, please consider only one cinema.

    Please list the tables that you would create including keys, foreign keys and attributes that are required by the user stories.

    ## User Stories

     **Movie exploration**
     * As a user I want to see which films can be watched and at what times
     * As a user I want to only see the shows which are not booked out

     **Show administration**
     * As a cinema owner I want to run different films at different times
     * As a cinema owner I want to run multiple films at the same time in different showrooms

     **Pricing**
     * As a cinema owner I want to get paid differently per show
     * As a cinema owner I want to give different seat types a percentage premium, for example 50 % more for vip seat

     **Seating**
     * As a user I want to book a seat
     * As a user I want to book a vip seat/couple seat/super vip/whatever
     * As a user I want to see which seats are still available
     * As a user I want to know where I'm sitting on my ticket
     * As a cinema owner I dont want to configure the seating for every show
     */
    public function up()
    {
        // Movies table
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->timestamps();
        });

        // Create showtimes table
        Schema::create('showtimes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained();
            $table->dateTime('start_time');
            $table->boolean('is_booked_out')->default(false);
            $table->timestamps();
        });

        // Create cinemas table
        Schema::create('cinemas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Create screens table
        Schema::create('screens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cinema_id')->constrained();
            $table->string('name');
            $table->integer('capacity');
            $table->timestamps();
        });

        // Create seat_categories table
        Schema::create('seat_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->float('premium_percentage')->default(0);
            $table->timestamps();
        });

        // Create seats table
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('screen_id')->constrained();
            $table->foreignId('seat_category_id')->constrained();
            $table->string('seat_number');
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });

        // Create bookings table
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('showtime_id')->constrained();
            $table->foreignId('seat_id')->constrained();
            $table->timestamps();
        });

        // Create payments table (optional)
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained();
            $table->decimal('amount', 8, 2);
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
        // throw new \Exception('implement in coding task 4, you can ignore this exception if you are just running the initial migrations.');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('seats');
        Schema::dropIfExists('seat_categories');
        Schema::dropIfExists('screens');
        Schema::dropIfExists('cinemas');
        Schema::dropIfExists('showtimes');
        Schema::dropIfExists('movies');
    }
}
