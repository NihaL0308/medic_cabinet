<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['patient', 'medecin', 'admin'])->default('patient')->after('email');
            $table->string('phone')->nullable()->after('role');
            $table->string('specialite')->nullable()->after('phone'); // pour médecins
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'specialite']);
        });
    }
};