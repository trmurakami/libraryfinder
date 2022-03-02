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
            $table->string('countryOfOrigin')->nullable();
            $table->string('datePublished')->nullable(); 
            $table->string('doi')->nullable();
            $table->string('educationEvent_name')->nullable();
            $table->string('inLanguage')->nullable();
            $table->string('isPartOf_isbn')->nullable();
            $table->string('isPartOf_issn')->nullable();
            $table->string('isPartOf_issueNumber')->nullable();
            $table->string('isPartOf_name')->nullable();
            $table->string('isPartOf_serieNumber')->nullable();
            $table->string('isPartOf_volumeNumber')->nullable();
            $table->string('locationCreated')->nullable();
            $table->string('name');
            $table->string('pageEnd')->nullable();
            $table->string('pageStart')->nullable();
            $table->string('publisher_organization_location')->nullable();
            $table->string('publisher_organization_name')->nullable();
            $table->string('record_source');
            $table->string('type');
            $table->string('type_schema_org');
            $table->string('url')->nullable();
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
