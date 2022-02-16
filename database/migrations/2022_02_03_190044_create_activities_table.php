<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();

            $table->text("name");
            $table->text("slug");
            $table->text("description");
            $table->text("excerpt");
            $table->dateTime("revealed_at")->nullable();
            $table->dateTime("event_at")->nullable();
            $table->bigInteger("leaderboard_type_id")->nullable();

            $table->bigInteger("parent_id")->nullable();
            $table->bigInteger("activity_type_id");
            $table->integer("tickets");
            $table->integer("limit");
            $table->text("image")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activities');
    }
}
