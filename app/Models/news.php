<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class news extends Model
{
     use  SoftDeletes;
    protected $table = 'news';

    protected $fillable = [
        'title',
        'slug',
        'topic_id',
        'event_type',
        'image',
        'image_caption',
        'short_intro',
        'full_content',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'views',
        'created_by',
        'updated_by',
        'is_visible',
        'show_on_homepage',
        'is_featured',
        'is_latest',
        'publish_status'
    ];


    public function topic()
    {
        return $this->belongsTo(topics::class, 'topic_id');
    }


    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
