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
        Schema::table('services', function (Blueprint $table) {
            $table->boolean('is_reported')->default(false);
            $table->string('report_reason')->nullable();
            $table->text('report_description')->nullable();
            $table->timestamp('reported_at')->nullable();
            $table->unsignedBigInteger('reported_by')->nullable();
            $table->foreign('reported_by')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->boolean('is_reported')->default(false);
            $table->string('report_reason')->nullable();
            $table->text('report_description')->nullable();
            $table->timestamp('reported_at')->nullable();
            $table->unsignedBigInteger('reported_by')->nullable();
            $table->foreign('reported_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['reported_by']);
            $table->dropColumn(['is_reported', 'report_reason', 'report_description', 'reported_at', 'reported_by']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['reported_by']);
            $table->dropColumn(['is_reported', 'report_reason', 'report_description', 'reported_at', 'reported_by']);
        });
    }
};
