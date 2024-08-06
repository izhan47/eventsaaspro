<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->integer('event_id')->unsigned();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->bigInteger('eventbrite_id')->nullable();
            $table->string('title');
            $table->float('amount');
            $table->enum('type', ['fixed', 'percent']);
            $table->boolean('status')->default(0);
            $table->date('start_date');
            $table->date('expire_date')->nullable();
            $table->longText('ticket');
            $table->integer('quantity')->default(0);
            $table->integer('quantity_sold')->default(0);
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
        Schema::dropIfExists('coupons');
    }
};
