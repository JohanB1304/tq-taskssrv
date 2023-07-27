<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ts_tasks_has_tags', function (Blueprint $table) {
            // $table->uuid('guid')->primary();
            $table->uuid('task_id')->index('idx_task_id');
            $table->uuid('tag_id')->index('isx_tag_id');
            $table->string('reference_entity', 255)->nullable();
            $table->string('type', 255)->nullable();
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
        Schema::dropIfExists('ts_tags');
    }
};