<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *    * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->integer('count_id')->unique();
            $table->string('invoice_code', 50);
            $table->date('invoice_date');
            $table->decimal('invoice_subtotal', 20, 2);
            $table->decimal('invoice_discount', 20, 2);
            $table->decimal('invoice_roundoff', 50, 2);
            $table->decimal('invoice_grand_total', 20, 2);
            $table->decimal('invoice_amount_paid', 20, 2);
            $table->decimal('invoice_amount_due', 20, 2);
            $table->text('invoice_note')->nullable();
            $table->string('payment_status', 25);
            $table->string('created_by');
            $table->foreign('customer_id')->references('id')->on('customers');
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
        Schema::dropIfExists('invoices');
    }
};
