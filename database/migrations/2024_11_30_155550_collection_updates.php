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
                // Collection Updates table
                Schema::create('collection_updates', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('librarian_id');
                    $table->unsignedBigInteger('book_id')->nullable();
                    $table->unsignedBigInteger('cd_id')->nullable();
                    $table->unsignedBigInteger('newspaper_id')->nullable();
                    $table->unsignedBigInteger('journal_id')->nullable();
                    $table->string('action'); // add/update/remove
                    $table->string('status')->default('pending'); // pending/approved/rejected
                    $table->timestamps();
        
                    $table->foreign('librarian_id')->references('id')->on('librarians')->onDelete('cascade');
                    $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
                    $table->foreign('cd_id')->references('id')->on('cds')->onDelete('cascade');
                    $table->foreign('newspaper_id')->references('id')->on('newspapers')->onDelete('cascade');
                    $table->foreign('journal_id')->references('id')->on('journals')->onDelete('cascade');
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_updates');
    }
};
