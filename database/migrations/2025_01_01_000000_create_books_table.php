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
            $table->string('book_name', 150);
            $table->text('description');
            $table->string('author', 150);
            $table->date('published_date');
            $table->timestamps();
            
            $table->unique(['book_name', 'author']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
