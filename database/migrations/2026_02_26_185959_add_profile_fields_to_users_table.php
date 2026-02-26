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
        Schema::table('users', function (Blueprint $table) {
             $table->string('apellido');
        $table->string('telefono');
        $table->string('direccion');
        $table->string('dni')->nullable()->unique();
        $table->string('role')->default('adopter');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
            'apellido',
            'telefono',
            'direccion',
            'dni',
            'role'
        ]);
        });
    }
};
