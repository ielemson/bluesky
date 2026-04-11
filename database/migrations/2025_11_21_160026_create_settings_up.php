<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            // SEO meta fields
            $table->text('meta_title')->nullable()->after('description');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->text('meta_keywords')->nullable()->after('meta_description');

            // Social meta (Open Graph, Twitter Cards)
            $table->text('og_title')->nullable()->after('meta_keywords');
            $table->text('og_description')->nullable()->after('og_title');
            $table->text('og_image')->nullable()->after('og_description');

            $table->text('twitter_title')->nullable()->after('og_image');
            $table->text('twitter_description')->nullable()->after('twitter_title');
            $table->text('twitter_image')->nullable()->after('twitter_description');

            // About Us content
            $table->longText('about_us')->nullable()->after('twitter_image');

            // Contact info (optional but useful for SEO & rich snippets)
            $table->string('contact_email')->nullable()->after('about_us');
            $table->string('contact_phone')->nullable()->after('contact_email');
            $table->string('contact_address')->nullable()->after('contact_phone');

            // Favicon & logo metadata
            $table->string('site_logo')->nullable()->after('contact_address');
            $table->string('site_favicon')->nullable()->after('site_logo');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'meta_title',
                'meta_description',
                'meta_keywords',
                'og_title',
                'og_description',
                'og_image',
                'twitter_title',
                'twitter_description',
                'twitter_image',
                'about_us',
                'contact_email',
                'contact_phone',
                'contact_address',
                'site_logo',
                'site_favicon'
            ]);
        });
    }
};

