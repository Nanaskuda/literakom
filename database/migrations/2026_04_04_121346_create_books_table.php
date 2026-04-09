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
            $table->string('judul');
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->string('penulis');
            $table->string('penerbit')->nullable();
            $table->integer('halaman')->nullable();
            $table->year('tahun_terbit');
            $table->text('sinopsis')->nullable();
            $table->string('cover')->nullable();
            $table->string('ebook')->nullable();
            $table->integer('stok')->default(0);
            $table->string('isbn')->unique()->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->softDeletes();
            $table->index(['judul', 'category_id', 'penulis', 'is_active']);
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
