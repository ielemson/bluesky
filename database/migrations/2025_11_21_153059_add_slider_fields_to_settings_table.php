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
       Schema::table('settings', function (Blueprint $table) {
            // Add new columns for slider functionality
            $table->string('slider_title_1')->nullable()->after('deleted_at');
            $table->string('slider_title_2')->nullable()->after('slider_title_1');
            $table->string('slider_title_3')->nullable()->after('slider_title_2');
            $table->string('slider_button_text')->nullable()->after('slider_title_3');
            $table->string('slider_link')->nullable()->after('slider_button_text');
            $table->string('slider_background')->nullable()->after('slider_link');
            $table->boolean('slider_active')->default(true)->after('slider_background');
            
            // Add index for better performance
            $table->index(['slider_active'], 'settings_slider_active_index');
        });
    }

    /**
     * Reverse the migrations.
     */
 public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            // Remove the added columns
            $table->dropColumn([
                'slider_title_1',
                'slider_title_2', 
                'slider_title_3',
                'slider_button_text',
                'slider_link',
                'slider_background',
                'slider_active'
            ]);
            
            // Drop the index
            $table->dropIndex('settings_slider_active_index');
        });
    }
};
