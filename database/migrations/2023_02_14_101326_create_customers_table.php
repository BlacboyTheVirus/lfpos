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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->integer('count_id')->unique();
            $table->string('customer_code', 10)->nullable()->unique();
            $table->string('customer_name', 255);
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            $table->decimal('customer_amount_due', 10, 2);
            $table->decimal('customer_invoice_due', 10, 2)->default(0.00);
            $table->string('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
};
