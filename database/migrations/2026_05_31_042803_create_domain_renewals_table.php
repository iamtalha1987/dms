<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('domain_renewals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('domain_id')->constrained()->cascadeOnDelete();
            $table->date('renewal_date');
            $table->date('new_expiry_date');
            $table->decimal('renewal_price', 12, 2)->default(0);
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->string('supplier_other')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();

            $table->index('renewal_date');
            $table->index('new_expiry_date');
            $table->index(['domain_id', 'new_expiry_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('domain_renewals');
    }
};
