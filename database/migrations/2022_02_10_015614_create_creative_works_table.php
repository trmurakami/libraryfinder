<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreativeWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creative_works', function (Blueprint $table) {
            $table->id();
            $table->string('countryOfOrigin');
            $table->string('datePublished'); 
            $table->string('doi');
            $table->string('inLanguage');
            $table->string('name');
            $table->string('record_source');
            $table->string('type');
            $table->string('type_schema_org');      
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
        Schema::dropIfExists('creative_works');
    }
}
