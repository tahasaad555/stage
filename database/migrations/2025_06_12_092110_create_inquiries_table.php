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
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            
            // Relationships
            $table->foreignId('annonce_id')->constrained('annonces')->onDelete('cascade');
            $table->foreignId('client_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('supplier_user_id')->constrained('users')->onDelete('cascade');
            
            // Inquiry details
            $table->string('subject');
            $table->text('message');
            
            // Client information (for guests or non-registered users)
            $table->string('client_name');
            $table->string('client_email');
            $table->string('client_phone')->nullable();
            
            // Inquiry metadata
            $table->enum('inquiry_type', ['general', 'purchase', 'lease', 'partnership', 'information'])->default('general');
            $table->string('budget_range')->nullable();
            $table->enum('preferred_contact_method', ['email', 'phone', 'both'])->default('email');
            
            // Status and management
            $table->enum('status', ['new', 'read', 'responded', 'closed', 'spam'])->default('new');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            
            // Response tracking
            $table->timestamp('responded_at')->nullable();
            $table->text('response_message')->nullable();
            $table->text('notes')->nullable();
            
            // Metadata
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->json('metadata')->nullable(); // For additional data
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['supplier_user_id', 'status']);
            $table->index(['annonce_id', 'created_at']);
            $table->index(['status', 'priority']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};