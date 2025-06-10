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
        Schema::table('users', function (Blueprint $table) {
            // Add role column if it doesn't exist
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'client', 'supplier'])->default('client')->after('email');
            }
            
            // Add additional columns if they don't exist
            if (!Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name')->after('id');
            }
            
            if (!Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name')->after('first_name');
            }
            
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('role');
            }
            
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('email_verified_at');
            }
            
            if (!Schema::hasColumn('users', 'newsletter_subscribed')) {
                $table->boolean('newsletter_subscribed')->default(false)->after('is_active');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove the columns we added (be careful with existing data)
            $columns = ['role', 'first_name', 'last_name', 'phone', 'is_active', 'last_login_at', 'newsletter_subscribed'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};