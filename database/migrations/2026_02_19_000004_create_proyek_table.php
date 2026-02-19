<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create proyek table
        Schema::create('proyek', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->string('icon', 50)->default('bi-folder');
            $table->string('warna', 20)->default('#4f46e5');
            $table->timestamps();
        });

        // 2. Add proyek_id to kriteria
        Schema::table('kriteria', function (Blueprint $table) {
            $table->foreignId('proyek_id')->nullable()->after('id')->constrained('proyek')->onDelete('cascade');
            $table->dropUnique(['kode']);
            $table->unique(['proyek_id', 'kode']);
        });

        // 3. Add proyek_id to alternatif
        Schema::table('alternatif', function (Blueprint $table) {
            $table->foreignId('proyek_id')->nullable()->after('id')->constrained('proyek')->onDelete('cascade');
            $table->dropUnique(['kode']);
            $table->unique(['proyek_id', 'kode']);
        });
    }

    public function down(): void
    {
        Schema::table('alternatif', function (Blueprint $table) {
            $table->dropForeign(['proyek_id']);
            $table->dropUnique(['proyek_id', 'kode']);
            $table->unique(['kode']);
            $table->dropColumn('proyek_id');
        });

        Schema::table('kriteria', function (Blueprint $table) {
            $table->dropForeign(['proyek_id']);
            $table->dropUnique(['proyek_id', 'kode']);
            $table->unique(['kode']);
            $table->dropColumn('proyek_id');
        });

        Schema::dropIfExists('proyek');
    }
};
