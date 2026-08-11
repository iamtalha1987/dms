<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->restrictOnDelete();
            $table->string('domain_name');
            $table->date('purchase_date');
            $table->decimal('purchase_price', 12, 2)->default(0);
            $table->date('current_expiry_date');
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->string('supplier_other')->nullable();
            $table->boolean('domain_managed_by_us')->default(false);
            $table->boolean('hosting_managed_by_us')->default(false);
            $table->enum('project_status', ['active', 'inactive', 'deactivated'])->default('active');
            $table->text('remarks')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('client_notified')->default(false);
            $table->timestamp('client_notified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique('domain_name');
            $table->index('current_expiry_date');
            $table->index('purchase_date');
            $table->index('project_status');
            $table->index('domain_managed_by_us');
            $table->index('hosting_managed_by_us');
            $table->index('client_notified');
            $table->index(['project_status', 'current_expiry_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};
