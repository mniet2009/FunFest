<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteEntryHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('entry_histories');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('entry_histories', function (Blueprint $table) {
            $table->id();

            $table->bigInteger("activity_id");
            $table->text("user_id");

            $table->text("proof");
            $table->bigInteger("result");

            $table->timestamps();
        });
    }
}
