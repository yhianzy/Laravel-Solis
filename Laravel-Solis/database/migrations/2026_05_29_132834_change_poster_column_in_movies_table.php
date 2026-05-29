<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('movies', function (Blueprint $table) {
        $table->longText('poster')->nullable()->change();
    });
}

public function down()
{
    Schema::table('movies', function (Blueprint $table) {
        $table->string('poster')->nullable()->change();
    });
}
};
