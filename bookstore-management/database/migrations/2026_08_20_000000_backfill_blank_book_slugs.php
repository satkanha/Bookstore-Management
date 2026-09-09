<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('books')
            ->whereNull('slug')
            ->orWhere('slug', '')
            ->orderBy('id')
            ->get(['id', 'title', 'isbn'])
            ->each(function (object $book): void {
                $base = Str::slug($book->title) ?: Str::slug($book->isbn) ?: "book-{$book->id}";
                $slug = $base;
                $counter = 2;

                while (DB::table('books')
                    ->where('slug', $slug)
                    ->where('id', '!=', $book->id)
                    ->exists()) {
                    $slug = "{$base}-{$counter}";
                    $counter++;
                }

                DB::table('books')
                    ->where('id', $book->id)
                    ->update(['slug' => $slug]);
            });
    }

    public function down(): void
    {
    }
};
