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
        Schema::create('prix_materiels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_matiere_premiere')
                ->nullable()
                ->constrained('matiere_premieres') // ou rien si la table suit le nom de la colonne sans le "id_"
                ->onDelete('set null');
            $table->decimal('prix', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prix_materiels');
    }
};
