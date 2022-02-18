<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreativeWorkPersonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creative_work_person', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('creative_work_id');
            $table->unsignedInteger('person_id');
            $table->string('function');
            $table->foreign('creative_work_id')->references('id')->on('creative_works')->onDelete('cascade');
            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('creative_work_person');
    }
}
