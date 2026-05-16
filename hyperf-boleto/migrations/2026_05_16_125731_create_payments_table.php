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
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string("status");
            $table->string("barcode")->index();
            $table->bigInteger("amount_cents");
            $table->string("currency", 3);
            $table->string("payer_document");
            $table->string("bank_authorization_id");
            $table->datetimes();
            $table->index(["status", "payer_document"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
