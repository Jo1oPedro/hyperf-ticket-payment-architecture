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
        Schema::create('webhook_deliveries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string("payment_id");
            $table->string("outcome");
            $table->string("payload_hash");
            $table->dateTime("received_at");
            $table->dateTime("processes_at")->nullable();
            $table->datetimes();
            $table->unique(["payment_id", "payload_hash"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_deliveries');
    }
};
