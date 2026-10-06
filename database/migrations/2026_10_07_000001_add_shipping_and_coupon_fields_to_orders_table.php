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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_company')->nullable()->after('payment_status');
            $table->string('tracking_number')->nullable()->after('shipping_company');
            $table->string('tracking_url')->nullable()->after('tracking_number');
            $table->timestamp('shipped_at')->nullable()->after('tracking_url');
            $table->timestamp('delivered_at')->nullable()->after('shipped_at');
            $table->string('coupon_code')->nullable()->after('delivered_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_company',
                'tracking_number',
                'tracking_url',
                'shipped_at',
                'delivered_at',
                'coupon_code',
            ]);
        });
    }
};
