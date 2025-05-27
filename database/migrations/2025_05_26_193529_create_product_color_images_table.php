<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductColorImagesTable extends Migration
{
    public function up(): void
    {
        Schema::create('product_color_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('color_id')->constrained()->onDelete('cascade');
            $table->string("image");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_color_images');
    }
}
