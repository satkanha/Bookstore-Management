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
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->foreignId('author_id')->constrained()->restrictOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('isbn')->unique();
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('stock')->default(0);
            $table->string('cover_image')->nullable();
            $table->date('publication_date')->nullable();
            $table->boolean('status')->default(true)->index();
            $table->boolean('featured')->default(false)->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category_id', 'author_id', 'status']);
            $table->index(['price', 'stock']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
