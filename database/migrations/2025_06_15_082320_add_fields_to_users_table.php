<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('apellido')->after('name');
            $table->string('cif')->unique()->after('apellido');
            $table->string('carrera')->after('cif');
        });
    }
    
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['apellido', 'cif', 'carrera']);
        });
    }

};
