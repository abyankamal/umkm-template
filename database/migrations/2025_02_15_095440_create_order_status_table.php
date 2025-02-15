<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderStatusTable extends Migration
{
    public function up()
    {
        // Create the order_status table
        Schema::create('order_status', function (Blueprint $table) {
            $table->id();
            $table->string('status_name'); // Field for status name
            $table->timestamps();
        });

        // Add foreign key to orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('order_status_id')->nullable()->after('status'); // Add order_status_id field
            $table->foreign('order_status_id')->references('id')->on('order_status')->onDelete('set null'); // Foreign key constraint
        });
    }

    public function down()
    {
        // Drop foreign key and column from orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['order_status_id']);
            $table->dropColumn('order_status_id');
        });

        // Drop the order_status table
        Schema::dropIfExists('order_status');
    }
}