<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type');
            $table->string('author')->nullable();
            $table->string('publisher')->nullable();
            $table->string('access_level')->default('restricted');
            $table->boolean('is_physical')->default(true);
            $table->string('file_path')->nullable();
            $table->date('publication_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
