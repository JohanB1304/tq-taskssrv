<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create(config('task.main_table_name'), function (Blueprint $table) {
            $table->uuid('guid')->primary();
            $table->string('ref_id');
            $table->string('ref_entity')->nullable();
            $table->string('title',64);
            $table->text('description')->nullable();
            $table->text('followup_notes')->nullable();
            $table->text('followup_file')->nullable();
            $table->text('verification_notes')->nullable();
            $table->text('verification_file')->nullable();
            $table->dateTime('date_start')->nullable();
            $table->dateTime('date_due')->nullable();
            $table->text('inactivation_notes')->nullable();
            $table->integer('order')->nullable();
            $table->tinyInteger('reopened')->default(0);
            $table->tinyInteger('disabled')->default(0);
            $table->dateTime('closed_at')->nullable();
            $table->string('closed_by', 64)->nullable();
            $table->text('status',16);
            $table->string('created_by', 64)->nullable();
            $table->string('updated_by', 64)->nullable();
            $table->string('expired_by', 64)->nullable();
            $table->dateTime('expired_at')->nullable();
            $table->string('tenant_id')->nullable();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists(config('task.main_table_name'));
    }
};