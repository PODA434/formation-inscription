<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inscriptions', function (Blueprint $t) {
            $t->json('formations')->nullable()->after('operateur'); // ex. ["web_maintenance_ia"]
        });
    }

    public function down(): void
    {
        Schema::table('inscriptions', function (Blueprint $t) {
            $t->dropColumn('formations');
        });
    }
};