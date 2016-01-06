<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetSharesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_shares', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('budget_id')->unsigned();
            $table->integer('from_customer_id')->unsigned();
            $table->integer('to_customer_id')->unsigned();
            $table->string('status', 10);
            $table->timestamps();

            $table->foreign('budget_id')
                ->references('id')->on('budgets')
                ->onDelete('cascade');

            $table->foreign('from_customer_id')
                ->references('id')->on('customers')
                ->onDelete('cascade');

            $table->foreign('to_customer_id')
                ->references('id')->on('customers')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('budget_shares');
    }
}
