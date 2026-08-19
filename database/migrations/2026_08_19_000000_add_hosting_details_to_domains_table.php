<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * NOTE: This is an additive migration on top of an already-running project.
     * It intentionally does NOT modify the original create_domains_table
     * migration so existing installs/data are not disturbed — it only relaxes
     * two columns to nullable and adds new hosting-related columns.
     */
    public function up(): void
    {
        Schema::table('domains', function (Blueprint $table) {
            // A domain that is not managed by us no longer requires a
            // purchase date or a current/renewal expiry date.
            $table->date('purchase_date')->nullable()->change();
            $table->date('current_expiry_date')->nullable()->change();

            // Collected instead when hosting (but not necessarily the
            // domain itself) is managed by us.
            $table->date('hosting_creation_date')->nullable();
            $table->boolean('hosting_lifetime')->default(false);
            $table->date('hosting_expiry_date')->nullable();
        });

        Schema::table('domains', function (Blueprint $table) {
            $table->index('hosting_expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->dropIndex(['hosting_expiry_date']);
        });

        Schema::table('domains', function (Blueprint $table) {
            $table->dropColumn(['hosting_creation_date', 'hosting_lifetime', 'hosting_expiry_date']);
            $table->date('purchase_date')->nullable(false)->change();
            $table->date('current_expiry_date')->nullable(false)->change();
        });
    }
};
