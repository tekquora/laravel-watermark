<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('watermark_settings', function (Blueprint $table) {
            $table->integer('watermark_image_size')->default(30)
                  ->comment('Percentage of base image width');
            $table->integer('watermark_image_opacity')->default(30)
                  ->comment('Opacity percentage 0-100');
            $table->integer('watermark_text_opacity')->default(40)
                  ->comment('Text Opacity percentage 0-100');
        });
    }

    public function down()
    {
        Schema::table('watermark_settings', function (Blueprint $table) {
            $table->dropColumn([
                'watermark_image_size',
                'watermark_image_opacity',
                'watermark_text_opacity'
            ]);
        });
    }
};
