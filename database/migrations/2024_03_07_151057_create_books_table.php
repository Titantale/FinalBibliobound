<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('isbn');
            $table->string('title');
            $table->string('author');
            $table->string('status');
            $table->longText('synopsis');
            $table->string('genre1')->nullable();
            $table->string('genre2')->nullable();
            $table->string('genre3')->nullable();
            $table->string('genre4')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
