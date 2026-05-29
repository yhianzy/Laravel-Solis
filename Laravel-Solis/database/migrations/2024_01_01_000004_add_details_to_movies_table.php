<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->string('poster')->nullable()->after('description');
            $table->string('director')->nullable()->after('poster');
            $table->string('cast')->nullable()->after('director');
            $table->integer('duration')->nullable()->after('cast');
            $table->string('language')->default('English')->after('duration');
            $table->enum('status', ['Watched', 'Unwatched', 'Watchlist'])->default('Unwatched')->after('language');
            $table->boolean('is_favorite')->default(false)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropColumn(['poster','director','cast','duration','language','status','is_favorite']);
        });
    }
};
