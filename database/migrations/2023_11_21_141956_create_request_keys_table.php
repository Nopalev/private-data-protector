<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('request_keys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id_owner');
            $table->unsignedBigInteger('user_id_req');
            $table->unsignedBigInteger('file_id');
            $table->foreign('user_id_owner')->references('id')->on('users');
            $table->foreign('user_id_req')->references('id')->on('users');
            $table->foreign('file_id')->references('id')->on('files');
            $table->enum('status', ['waiting', 'accepted', 'declined']);
            $table->longText('symmetricKey');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_keys');
    }
};
