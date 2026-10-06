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
        Schema::table('products', function (Blueprint $table) {
            $table->index('is_active');
            $table->index('gender');
            $table->index('price');
            $table->index('view_count');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('access_token', 64)->nullable()->index()->after('order_number');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['gender']);
            $table->dropIndex(['price']);
            $table->dropIndex(['view_count']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['access_token']);
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
            $table->dropColumn('access_token');
        });
    }
};
