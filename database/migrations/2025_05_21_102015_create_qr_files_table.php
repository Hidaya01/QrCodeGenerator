<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('qr_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qr_code_id')->constrained()->onDelete('cascade');
            $table->string('original_name');
            $table->string('storage_path');
            $table->string('mime_type');
            $table->integer('size');
            $table->enum('type', ['pdf', 'vcard']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('qr_files');
    }
};