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
        Schema::table('comedian_events', function (Blueprint $table) {
            $table->boolean('fixed')->default(0);
            $table->boolean('percent')->default(0);
            $table->float('fixed_value')->nullable();
            $table->float('percent_value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comedian_events', function (Blueprint $table) {
            $table->dropColumn('fixed');
            $table->dropColumn('percent');
            $table->dropColumn('fixed_value');
            $table->dropColumn('percent_value');
        });
    }
};
