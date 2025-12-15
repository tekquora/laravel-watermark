<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('watermark_settings', function (Blueprint $table) {
            $table->id();

            $table->boolean('use_image_watermark')->default(false);

            $table->enum('image_watermark_type', ['image', 'text'])
                  ->default('text');

            $table->string('watermark_image')->nullable();

            $table->string('watermark_text')->nullable();

            $table->unsignedInteger('watermark_text_size')->default(20);

            $table->string('watermark_text_color')->default('#cccccc');

            $table->string('watermark_position')->default('bottom-right');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('watermark_settings');
    }
};
