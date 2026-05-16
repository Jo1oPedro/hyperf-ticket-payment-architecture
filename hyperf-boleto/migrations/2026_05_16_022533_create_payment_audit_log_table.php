<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_audit_log', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string("payment_id");
            $table->string("event");
            $table->string("before_status")->nullable();
            $table->string("after_status");
            $table->string("actor");
            $table->datetimes();
            $table->index(["payment_id", "created_at"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_audit_log');
    }
};
