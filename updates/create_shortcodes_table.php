<?php

namespace Ompmega\BlogShortcodes\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateShortcodesTable extends Migration
{
    public function up()
    {
        Schema::create('ompmega_blogshortcodes_shortcodes', function($table)
        {
            /** @var \October\Rain\Database\Schema\Blueprint $table */
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('code')->index();
            $table->string('name')->index()->nullable();
            $table->boolean('has_preview')->default(0);
            $table->boolean('is_enabled')->default(1);
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('ompmega_blogshortcodes_shortcodes');
    }
}
