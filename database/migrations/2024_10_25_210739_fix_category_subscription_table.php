<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Paso 1: Asegurarse de que las tablas categories y subscriptions usen InnoDB
        DB::statement('ALTER TABLE categories ENGINE = InnoDB');
        DB::statement('ALTER TABLE subscriptions ENGINE = InnoDB');

        // Paso 2: Verificar y corregir la estructura de la tabla categories si es necesario
        if (!Schema::hasColumn('categories', 'id')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->id()->first();
            });
        }

        // Paso 3: Verificar y corregir la estructura de la tabla subscriptions si es necesario
        if (!Schema::hasColumn('subscriptions', 'id')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->id()->first();
            });
        }

        // Paso 4: Eliminar la tabla category_subscription si existe
        Schema::dropIfExists('category_subscription');

        // Paso 5: Crear la tabla category_subscription
        Schema::create('category_subscription', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('subscription_id');
            $table->timestamps();
        });

        // Paso 6: Añadir las claves foráneas
        Schema::table('category_subscription', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('category_subscription');
    }
};