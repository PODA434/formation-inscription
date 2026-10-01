<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscriptions', function (Blueprint $t) {
            $t->id();
            $t->uuid('reference')->unique();                 // utilisée dans l'URL de suivi du participant
            $t->string('prenom');
            $t->string('nom');
            $t->string('email')->index();
            $t->string('telephone');                         // numéro avec lequel le participant a payé
            $t->string('ville')->nullable();
            $t->string('profession')->nullable();
            $t->string('operateur', 10);                     // orange | moov
            $t->string('reference_transaction')->unique();   // identifiant reçu par SMS après le paiement
            $t->unsignedInteger('montant');                  // montant attendu, fixé côté serveur
            $t->string('devise', 3)->default('XOF');
            $t->string('statut')->default('en_attente_verification')->index(); // en_attente_verification | payee | refusee
            $t->string('motif_refus')->nullable();
            $t->timestamp('valide_le')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscriptions');
    }
};
