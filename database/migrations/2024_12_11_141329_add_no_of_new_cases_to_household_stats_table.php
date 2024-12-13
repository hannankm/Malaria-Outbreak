<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNoOfNewCasesToHouseholdStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('household_stats', function (Blueprint $table) {
            // Add the new column
            $table->integer('no_of_new_cases')->default(0)->after('no_of_recovered');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('household_stats', function (Blueprint $table) {
            // Drop the column in case of rollback
            $table->dropColumn('no_of_new_cases');
        });
    }
}
