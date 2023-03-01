<?php

namespace Xitara\Gallery\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class CreateGalleriesTable extends Migration
{
    public function up()
    {
        Schema::create('xitara_gallery_galleries', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 191)->nullable();
            $table->string('slug', 191)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_slider')->default(0);
            $table->boolean('is_lightbox')->default(0);
            $table->boolean('is_active')->default(0);
            $table->mediumtext('options')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('xitara_gallery_galleries');
    }
}
