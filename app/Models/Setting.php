<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'meta_title',
        'meta_description', 
        'meta_keywords',
        'sort_order',
        'contact_email',
        'contact_phone',
        'contact_address',
        'about_us',
        'og_title',
        'og_description',
        'og_image',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'site_logo',
        'site_favicon'
    ];
}