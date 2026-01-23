<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_blocked')->default(false);
            $table->text('block_reason')->nullable();
            $table->timestamp('blocked_at')->nullable();
            $table->unsignedBigInteger('blocked_by')->nullable();
            $table->timestamp('blocked_until')->nullable(); // Blocage temporaire
            $table->integer('warning_count')->default(0);
            $table->timestamp('last_warning_at')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->text('deletion_reason')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            
            $table->foreign('blocked_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
            
            $table->index(['is_blocked', 'blocked_at']);
            $table->index(['is_deleted', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['blocked_by']);
            $table->dropForeign(['deleted_by']);
            $table->dropColumn([
                'is_blocked',
                'block_reason', 
                'blocked_at',
                'blocked_by',
                'blocked_until',
                'warning_count',
                'last_warning_at',
                'is_deleted',
                'deletion_reason',
                'deleted_at',
                'deleted_by'
            ]);
        });
    }
};
