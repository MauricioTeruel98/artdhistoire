<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // Eliminar columnas si existen
            if (Schema::hasColumn('subscriptions', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
            if (Schema::hasColumn('subscriptions', 'stripe_subscription_id')) {
                $table->dropColumn('stripe_subscription_id');
            }
            if (Schema::hasColumn('subscriptions', 'paypal_subscription_id')) {
                $table->dropColumn('paypal_subscription_id');
            }

            // Añadir columnas solo si no existen
            if (!Schema::hasColumn('subscriptions', 'amount')) {
                $table->decimal('amount', 8, 2)->default(49.00);
            }
            if (!Schema::hasColumn('subscriptions', 'status')) {
                $table->string('status')->default('active');
            }
        });

        // Asegúrate de que la tabla categories existe y usa InnoDB
        Schema::table('categories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
        });

        // Crear tabla pivot solo si no existe
        if (!Schema::hasTable('category_subscription')) {
            Schema::create('category_subscription', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('category_id');
                $table->unsignedBigInteger('subscription_id');
                $table->timestamps();

                $table->foreign('category_id')
                      ->references('id')
                      ->on('categories')
                      ->onDelete('cascade');
                $table->foreign('subscription_id')
                      ->references('id')
                      ->on('subscriptions')
                      ->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('category_subscription')) {
            Schema::dropIfExists('category_subscription');
        }

        Schema::table('subscriptions', function (Blueprint $table) {
            // Revertir cambios solo si las columnas existen
            if (!Schema::hasColumn('subscriptions', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }
            if (!Schema::hasColumn('subscriptions', 'stripe_subscription_id')) {
                $table->string('stripe_subscription_id')->nullable();
            }
            if (!Schema::hasColumn('subscriptions', 'paypal_subscription_id')) {
                $table->string('paypal_subscription_id')->nullable();
            }
            if (Schema::hasColumn('subscriptions', 'amount')) {
                $table->dropColumn('amount');
            }
            if (Schema::hasColumn('subscriptions', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};